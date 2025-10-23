<?php

namespace App\Services;

use App\Models\History;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService extends BaseService
{
    protected $model = History::class;
    protected $orderByColumn = 'trx_date';
    protected $orderByDirection = 'desc';

    /**
     * Get analytics data with caching
     *
     * @param Request $request
     * @return array
     */
    public function getAnalyticsData(Request $request): array
    {
        $dateFrom = $request->input('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $clientId = $request->input('client_id');
        $serviceId = $request->input('service_id');

        // Generate cache key for this specific query
        $cacheKey = "analytics_data_{$dateFrom}_{$dateTo}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');

        // Cache for 10 minutes
        return cache()->remember($cacheKey, 10, function () use ($dateFrom, $dateTo, $clientId, $serviceId) {
            return $this->buildAnalyticsData($dateFrom, $dateTo, $clientId, $serviceId);
        });
    }

    /**
     * Build analytics data from histories table
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @param int|null $clientId
     * @param int|null $serviceId
     * @return array
     */
    private function buildAnalyticsData(string $dateFrom, string $dateTo, ?int $clientId, ?int $serviceId): array
    {
        $query = History::query()
            ->whereBetween('trx_date', [$dateFrom, $dateTo])
            ->with(['user', 'client', 'service']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($serviceId) {
            $query->where('module_id', $serviceId);
        }

        $histories = $query->get();

        // Calculate statistics
        $totalTransactions = $histories->count();
        $totalRevenue = $histories->sum('price');
        $totalDuration = $histories->sum('duration');
        $uniqueUsers = $histories->unique('user_id')->count();
        $uniqueClients = $histories->unique('client_id')->count();

        // Transaction types breakdown
        $transactionTypes = $histories->groupBy('trx_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'percentage' => 0 // Will be calculated later
            ];
        });

        // Calculate percentages
        $totalCount = $totalTransactions;
        $transactionTypes = $transactionTypes->map(function ($data) use ($totalCount) {
            $data['percentage'] = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
            return $data;
        });

        // Client types breakdown
        $clientTypes = $histories->groupBy('client_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'percentage' => 0 // Will be calculated later
            ];
        });

        // Calculate percentages for client types
        $clientTypes = $clientTypes->map(function ($data) use ($totalCount) {
            $data['percentage'] = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
            return $data;
        });

        // Top clients by revenue
        $topClients = $histories->groupBy('client_id')->map(function ($group) {
            $firstHistory = $group->first();
            return [
                'client_id' => $firstHistory->client_id,
                'client_name' => $firstHistory->client->client_name ?? 'Unknown',
                'client_type' => $firstHistory->client_type,
                'transaction_count' => $group->count(),
                'total_revenue' => $group->sum('price'),
                'total_duration' => $group->sum('duration'),
            ];
        })->sortByDesc('total_revenue')->take(10);

        // Top services by usage
        $topServices = $histories->groupBy('module_id')->map(function ($group) {
            $firstHistory = $group->first();
            return [
                'service_id' => $firstHistory->module_id,
                'service_name' => $firstHistory->service->name ?? 'Unknown',
                'usage_count' => $group->count(),
                'total_revenue' => $group->sum('price'),
                'total_duration' => $group->sum('duration'),
            ];
        })->sortByDesc('usage_count')->take(10);

        // Daily trends
        $dailyTrends = $histories->groupBy(function ($history) {
            return Carbon::parse($history->trx_date)->format('Y-m-d');
        })->map(function ($group) {
            return [
                'date' => $group->first()->trx_date,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
        })->sortBy('date');

        // Revenue trends
        $revenueTrends = $histories->groupBy(function ($history) {
            return Carbon::parse($history->trx_date)->format('Y-m-d');
        })->map(function ($group) {
            return [
                'date' => $group->first()->trx_date,
                'revenue' => $group->sum('price'),
            ];
        })->sortBy('date');

        // Status breakdown
        $statusBreakdown = $histories->groupBy('status')->map(function ($group) {
            return [
                'status' => $group->first()->status,
                'count' => $group->count(),
                'percentage' => 0 // Will be calculated later
            ];
        });

        // Calculate percentages for status
        $statusBreakdown = $statusBreakdown->map(function ($data) use ($totalCount) {
            $percentage = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
            return [
                'status' => $data['status'],
                'count' => $data['count'],
                'percentage' => $percentage
            ];
        });

        // Charge vs non-charge breakdown
        $chargeBreakdown = $histories->groupBy('is_charge')->map(function ($group) {
            $isCharge = $group->first()->is_charge;
            return [
                'type' => $isCharge ? 'Charge' : 'Non-Charge',
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'percentage' => 0 // Will be calculated later
            ];
        });

        // Calculate percentages for charge breakdown
        $chargeBreakdown = $chargeBreakdown->map(function ($data) use ($totalCount) {
            $percentage = $totalCount > 0 ? round(($data['count'] / $totalCount) * 100, 2) : 0;
            return [
                'type' => $data['type'],
                'count' => $data['count'],
                'revenue' => $data['revenue'],
                'percentage' => $percentage
            ];
        });

        // Get additional data for the view
        $totalClients = Client::count();
        $activeClients = Client::where('is_active', 1)->count();
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', 1)->count();

        // Get transaction status counts
        $successfulTransactions = $histories->where('status', 'success')->count();
        $failedTransactions = $histories->where('status', 'failed')->count();
        $pendingTransactions = $histories->where('status', 'pending')->count();

        // Get revenue data
        $todayRevenue = $histories->where('status', 'success')
            ->where('is_charge', 1)
            ->filter(function ($history) {
                return $history->trx_date && Carbon::parse($history->trx_date)->isToday();
            })
            ->sum('price');

        $thisMonthRevenue = $histories->where('status', 'success')
            ->where('is_charge', 1)
            ->filter(function ($history) {
                return $history->trx_date &&
                       Carbon::parse($history->trx_date)->month === Carbon::now()->month &&
                       Carbon::parse($history->trx_date)->year === Carbon::now()->year;
            })
            ->sum('price');

        // Get recent transactions
        $recentTransactions = History::with(['client', 'service', 'currency'])
            ->orderBy('trx_date', 'desc')
            ->limit(10)
            ->get();

        return [
            'totalClients' => $totalClients,
            'activeClients' => $activeClients,
            'totalServices' => $totalServices,
            'activeServices' => $activeServices,
            'totalTransactions' => $totalTransactions,
            'successfulTransactions' => $successfulTransactions,
            'failedTransactions' => $failedTransactions,
            'pendingTransactions' => $pendingTransactions,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'recentTransactions' => $recentTransactions,
            'totalDuration' => $totalDuration,
            'uniqueUsers' => $uniqueUsers,
            'uniqueClients' => $uniqueClients,
            'transactionTypes' => $transactionTypes,
            'clientTypes' => $clientTypes,
            'topClients' => $topClients,
            'topServices' => $topServices,
            'dailyTrends' => $dailyTrends,
            'revenueTrends' => $revenueTrends,
            'statusBreakdown' => $statusBreakdown,
            'chargeBreakdown' => $chargeBreakdown,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'clientId' => $clientId,
            'serviceId' => $serviceId,
        ];
    }

    /**
     * Get analytics data for AJAX requests
     *
     * @param Request $request
     * @return array
     */
    public function getData(Request $request): array
    {
        return $this->getAnalyticsData($request);
    }

    /**
     * Export analytics data
     *
     * @param Request $request
     * @param string $format
     * @return array
     */
    public function export(Request $request, string $format = 'excel'): array
    {
        $analyticsData = $this->getAnalyticsData($request);

        // Prepare data for export
        $exportData = [
            'summary' => [
                'Total Transactions' => $analyticsData['totalTransactions'],
                'Total Revenue' => $analyticsData['totalRevenue'],
                'Total Duration' => $analyticsData['totalDuration'],
                'Unique Users' => $analyticsData['uniqueUsers'],
                'Unique Clients' => $analyticsData['uniqueClients'],
            ],
            'transaction_types' => $analyticsData['transactionTypes'],
            'client_types' => $analyticsData['clientTypes'],
            'top_clients' => $analyticsData['topClients'],
            'top_services' => $analyticsData['topServices'],
            'daily_trends' => $analyticsData['dailyTrends'],
            'status_breakdown' => $analyticsData['statusBreakdown'],
            'charge_breakdown' => $analyticsData['chargeBreakdown'],
        ];

        return $exportData;
    }

    /**
     * Clear analytics cache
     *
     * @param array $params
     * @return void
     */
    public function clearCache(array $params = []): void
    {
        $dateFrom = $params['date_from'] ?? null;
        $dateTo = $params['date_to'] ?? null;
        $clientId = $params['client_id'] ?? null;
        $serviceId = $params['service_id'] ?? null;

        if ($dateFrom && $dateTo) {
            $cacheKey = "analytics_data_{$dateFrom}_{$dateTo}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');
            cache()->forget($cacheKey);
        } else {
            // Clear all analytics cache patterns
            cache()->flush();
        }
    }
}
