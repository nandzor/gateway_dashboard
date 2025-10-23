<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Balance;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BalanceController extends BaseController
{
    /**
     * Check client balance
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkBalance(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|integer|exists:clients,id'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $clientId = $request->input('client_id');

            // Get client information
            $client = Client::find($clientId);
            if (!$client) {
                return $this->notFoundResponse('Client not found');
            }

            // Get client balance
            $balance = Balance::where('client_id', $clientId)->first();

            // If no balance record exists, create one with default values
            if (!$balance) {
                $balance = Balance::create([
                    'client_id' => $clientId,
                    'balance' => 0,
                    'quota' => 0
                ]);
            }

            // Prepare response data
            $responseData = [
                'client' => [
                    'id' => $client->id,
                    'name' => $client->client_name,
                    'type' => $client->type == 1 ? 'Prepaid' : 'Postpaid',
                    'is_active' => (bool) $client->is_active,
                    'created_at' => $client->created_at->toISOString(),
                ],
                'balance' => [
                    'current_balance' => (float) $balance->balance,
                    'quota' => (int) $balance->quota,
                    'status' => $this->getBalanceStatus((float) $balance->balance),
                    'last_updated' => $balance->updated_at->toISOString(),
                ],
                'summary' => [
                    'has_balance' => $balance->isPositive(),
                    'is_zero' => $balance->isZero(),
                    'is_negative' => $balance->isNegative(),
                    'can_transact' => $balance->isPositive() || $client->type == 2, // Postpaid can transact even with zero balance
                ]
            ];

            return $this->successResponse($responseData, 'Client balance retrieved successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve client balance: ' . $e->getMessage());
        }
    }

    /**
     * Get balance status based on amount
     *
     * @param float $balance
     * @return string
     */
    private function getBalanceStatus(float $balance): string
    {
        if ($balance > 0) {
            return 'positive';
        } elseif ($balance == 0) {
            return 'zero';
        } else {
            return 'negative';
        }
    }

    /**
     * Get balance history for a client
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBalanceHistory(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|integer|exists:clients,id',
                'limit' => 'sometimes|integer|min:1|max:100',
                'offset' => 'sometimes|integer|min:0'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $clientId = $request->input('client_id');
            $limit = $request->input('limit', 20);
            $offset = $request->input('offset', 0);

            // Get client information
            $client = Client::find($clientId);
            if (!$client) {
                return $this->notFoundResponse('Client not found');
            }

            // Get balance history from histories table
            $histories = \App\Models\History::where('client_id', $clientId)
                ->where('is_charge', 1) // Only charged transactions
                ->orderBy('trx_date', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();

            $historyData = $histories->map(function ($history) {
                return [
                    'transaction_id' => $history->trx_id,
                    'amount' => (float) $history->price,
                    'type' => $history->trx_type == 1 ? 'debit' : 'credit',
                    'service' => $history->service->name ?? 'Unknown',
                    'date' => $history->trx_date ? $history->trx_date->toISOString() : null,
                    'status' => $history->status,
                ];
            });

            $responseData = [
                'client' => [
                    'id' => $client->id,
                    'name' => $client->client_name,
                ],
                'history' => $historyData,
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'count' => $historyData->count(),
                ]
            ];

            return $this->successResponse($responseData, 'Balance history retrieved successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve balance history: ' . $e->getMessage());
        }
    }

    /**
     * Update client balance (for admin use)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateBalance(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|integer|exists:clients,id',
                'balance' => 'required|numeric',
                'quota' => 'sometimes|integer|min:0',
                'reason' => 'sometimes|string|max:255'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $clientId = $request->input('client_id');
            $newBalance = $request->input('balance');
            $quota = $request->input('quota');
            $reason = $request->input('reason', 'Balance updated via API');

            // Get or create balance record
            $balance = Balance::where('client_id', $clientId)->first();
            if (!$balance) {
                $balance = Balance::create([
                    'client_id' => $clientId,
                    'balance' => 0,
                    'quota' => 0
                ]);
            }

            $oldBalance = $balance->balance;
            $oldQuota = $balance->quota;

            // Update balance
            $balance->setBalance($newBalance);

            // Update quota if provided
            if ($quota !== null) {
                $balance->setQuota($quota);
            }

            $responseData = [
                'client_id' => $clientId,
                'balance' => [
                    'previous' => (float) $oldBalance,
                    'current' => (float) $balance->balance,
                    'quota' => (int) $balance->quota,
                    'change' => (float) ($balance->balance - $oldBalance),
                ],
                'reason' => $reason,
                'updated_at' => $balance->updated_at->toISOString(),
            ];

            return $this->successResponse($responseData, 'Client balance updated successfully');

        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update client balance: ' . $e->getMessage());
        }
    }
}
