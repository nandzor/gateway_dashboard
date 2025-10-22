<?php

namespace App\Services;

use App\Models\History;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HistoryService extends BaseService
{
    /**
     * HistoryService constructor.
     */
    public function __construct()
    {
        $this->model = new History();
        $this->searchableFields = ['trx_id', 'trx_req', 'remote_ip'];
        $this->orderByColumn = 'created_at';
        $this->orderByDirection = 'desc';
    }

    /**
     * Get all histories with optional filters
     */
    public function getAllHistories(?string $search = null, int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->with(['client:id,client_name', 'service:id,name']);

        // Apply filters
        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        // Apply search if provided
        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get histories by client
     */
    public function getHistoriesByClient(int $clientId, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->with(['client:id,client_name', 'service:id,name'])
            ->where('client_id', $clientId);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get histories by service
     */
    public function getHistoriesByService(int $serviceId, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->with(['client:id,client_name', 'service:id,name'])
            ->where('module_id', $serviceId);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get histories by status
     */
    public function getHistoriesByStatus(string $status, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->with(['client:id,client_name', 'service:id,name'])
            ->where('status', $status);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get histories by transaction type
     */
    public function getHistoriesByTransactionType(int $type, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $perPage = $this->validatePerPage($perPage);
        $query = $this->model->with(['client:id,client_name', 'service:id,name'])
            ->where('trx_type', $type);

        if (!empty($search)) {
            $query = $this->applySearch($query, $search);
        }

        return $query->orderBy($this->orderByColumn, $this->orderByDirection)->paginate($perPage);
    }

    /**
     * Get history by ID
     */
    public function getHistoryById(int $id): ?History
    {
        return $this->model->with(['client', 'service'])->find($id);
    }

    /**
     * Create new history record
     */
    public function createHistory(array $data): History
    {
        return $this->model->create($data);
    }

    /**
     * Update history record
     */
    public function updateHistory(History $history, array $data): bool
    {
        return $history->update($data);
    }

    /**
     * Delete history record
     */
    public function deleteHistory(History $history): bool
    {
        return $history->delete();
    }

    /**
     * Get history statistics
     */
    public function getHistoryStats(): array
    {
        return [
            'total' => $this->model->count(),
            'successful' => $this->model->where('is_charge', 1)->count(),
            'failed' => $this->model->where('is_charge', 0)->count(),
            'pending' => $this->model->pending()->count(),
            'total_price' => $this->model->where('is_charge', 1)->sum('price'),
            'credit_price' => $this->model->byType(1)->where('is_charge', 1)->sum('price'),
            'debit_price' => $this->model->byType(2)->where('is_charge', 1)->sum('price'),
            'charged_transactions' => $this->model->charged()->count(),
            'local_transactions' => $this->model->local()->count(),
            'dashboard_transactions' => $this->model->where('is_dashboard', 1)->count(),
        ];
    }

    /**
     * Get recent histories
     */
    public function getRecentHistories(int $limit = 10): Collection
    {
        return $this->model->with(['client:id,client_name', 'service:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get per page options
     */
    public function getPerPageOptions(): array
    {
        return [10, 15, 25, 50, 100];
    }

    /**
     * Get transaction type options
     */
    public function getTransactionTypeOptions(): array
    {
        return [
            1 => 'Credit',
            2 => 'Debit',
            3 => 'Refund',
            4 => 'Adjustment',
        ];
    }

    /**
     * Get status options
     */
    public function getStatusOptions(): array
    {
        return [
            'success' => 'Success',
            'failed' => 'Failed',
            'pending' => 'Pending',
            'error' => 'Error',
        ];
    }

    /**
     * Export histories to CSV
     */
    public function exportHistories(array $filters = []): string
    {
        $query = $this->model->with(['client:id,client_name', 'service:id,name']);

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        $histories = $query->orderBy('created_at', 'desc')->get();

        $filename = 'histories_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/exports/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $file = fopen($filepath, 'w');

        // Write CSV header
        fputcsv($file, [
            'ID',
            'User ID',
            'Client',
            'Client Type',
            'Transaction ID',
            'Transaction Type',
            'Transaction Date',
            'Service',
            'Price',
            'Duration',
            'Is Charge',
            'Remote IP',
            'Is Local',
            'Status',
            'Request ID',
            'Node ID',
            'Is Dashboard',
            'Currency ID',
            'Created At'
        ]);

        // Write data
        foreach ($histories as $history) {
            fputcsv($file, [
                $history->id,
                $history->user_id,
                $history->client->client_name ?? 'N/A',
                $history->client_type_display,
                $history->trx_id,
                $history->transaction_type_display,
                $history->trx_date ? $history->trx_date->format('Y-m-d H:i:s') : 'N/A',
                $history->service->name ?? 'N/A',
                $history->price,
                $history->formatted_duration,
                $history->charge_status_display,
                $history->remote_ip,
                $history->local_status_display,
                $history->status_display,
                $history->trx_req,
                $history->node_id,
                $history->dashboard_status_display,
                $history->currency_id,
                $history->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($file);

        return $filepath;
    }
}
