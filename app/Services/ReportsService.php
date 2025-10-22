<?php

namespace App\Services;

use App\Models\History;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsService extends BaseService
{
    protected $model = History::class;
    protected $orderByColumn = 'trx_date';
    protected $orderByDirection = 'desc';

    /**
     * Get daily report data with caching
     *
     * @param Request $request
     * @return array
     */
    public function getDailyReportData(Request $request): array
    {
        $date = $request->input('date', now()->toDateString());
        $clientId = $request->input('client_id');
        $serviceId = $request->input('service_id');

        // Generate cache key for this specific query
        $cacheKey = "daily_report_{$date}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');

        // Cache for 10 minutes
        return cache()->remember($cacheKey, 10, function () use ($date, $clientId, $serviceId) {
            return $this->buildDailyReportData($date, $clientId, $serviceId);
        });
    }

    /**
     * Get monthly report data with caching
     *
     * @param Request $request
     * @return array
     */
    public function getMonthlyReportData(Request $request): array
    {
        $month = $request->input('month', now()->format('Y-m'));
        $clientId = $request->input('client_id');
        $serviceId = $request->input('service_id');

        // Generate cache key for this specific query
        $cacheKey = "monthly_report_{$month}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');

        // Cache for 10 minutes
        return cache()->remember($cacheKey, 10, function () use ($month, $clientId, $serviceId) {
            return $this->buildMonthlyReportData($month, $clientId, $serviceId);
        });
    }

    /**
     * Build daily report data from histories table
     *
     * @param string $date
     * @param int|null $clientId
     * @param int|null $serviceId
     * @return array
     */
    private function buildDailyReportData(string $date, ?int $clientId, ?int $serviceId): array
    {
        $query = History::query()
            ->whereDate('trx_date', $date)
            ->with(['user', 'client', 'service']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($serviceId) {
            $query->where('module_id', $serviceId);
        }

        $histories = $query->get();

        // Calculate daily statistics
        $totalTransactions = $histories->count();
        $totalRevenue = $histories->sum('price');
        $totalDuration = $histories->sum('duration');
        $uniqueUsers = $histories->unique('user_id')->count();
        $uniqueClients = $histories->unique('client_id')->count();

        // Transaction types breakdown
        $transactionTypes = $histories->groupBy('trx_type')->map(function ($group) {
            return [
                'type' => $group->first()->trx_type,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
        });

        // Client types breakdown
        $clientTypes = $histories->groupBy('client_type')->map(function ($group) {
            return [
                'type' => $group->first()->client_type,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
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

        // Hourly trends
        $hourlyTrends = $histories->groupBy(function ($history) {
            return Carbon::parse($history->trx_date)->format('H:00');
        })->map(function ($group) {
            return [
                'hour' => $group->first()->trx_date,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
        })->sortBy('hour');

        // Status breakdown
        $statusBreakdown = $histories->groupBy('status')->map(function ($group) {
            return [
                'status' => $group->first()->status,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
            ];
        });

        // Charge vs non-charge breakdown
        $chargeBreakdown = $histories->groupBy('is_charge')->map(function ($group) {
            $isCharge = $group->first()->is_charge;
            return [
                'type' => $isCharge ? 'Charge' : 'Non-Charge',
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
            ];
        });

        return [
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'totalDuration' => $totalDuration,
            'uniqueUsers' => $uniqueUsers,
            'uniqueClients' => $uniqueClients,
            'transactionTypes' => $transactionTypes,
            'clientTypes' => $clientTypes,
            'topClients' => $topClients,
            'topServices' => $topServices,
            'hourlyTrends' => $hourlyTrends,
            'statusBreakdown' => $statusBreakdown,
            'chargeBreakdown' => $chargeBreakdown,
            'date' => $date,
            'clientId' => $clientId,
            'serviceId' => $serviceId,
        ];
    }

    /**
     * Build monthly report data from histories table
     *
     * @param string $month
     * @param int|null $clientId
     * @param int|null $serviceId
     * @return array
     */
    private function buildMonthlyReportData(string $month, ?int $clientId, ?int $serviceId): array
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        $query = History::query()
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->with(['user', 'client', 'service']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($serviceId) {
            $query->where('module_id', $serviceId);
        }

        $histories = $query->get();

        // Calculate monthly statistics
        $totalTransactions = $histories->count();
        $totalRevenue = $histories->sum('price');
        $totalDuration = $histories->sum('duration');
        $uniqueUsers = $histories->unique('user_id')->count();
        $uniqueClients = $histories->unique('client_id')->count();

        // Transaction types breakdown
        $transactionTypes = $histories->groupBy('trx_type')->map(function ($group) {
            return [
                'type' => $group->first()->trx_type,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
        });

        // Client types breakdown
        $clientTypes = $histories->groupBy('client_type')->map(function ($group) {
            return [
                'type' => $group->first()->client_type,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
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

        // Weekly trends
        $weeklyTrends = $histories->groupBy(function ($history) {
            return Carbon::parse($history->trx_date)->format('Y-W');
        })->map(function ($group) {
            return [
                'week' => $group->first()->trx_date,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
                'duration' => $group->sum('duration'),
            ];
        })->sortBy('week');

        // Status breakdown
        $statusBreakdown = $histories->groupBy('status')->map(function ($group) {
            return [
                'status' => $group->first()->status,
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
            ];
        });

        // Charge vs non-charge breakdown
        $chargeBreakdown = $histories->groupBy('is_charge')->map(function ($group) {
            $isCharge = $group->first()->is_charge;
            return [
                'type' => $isCharge ? 'Charge' : 'Non-Charge',
                'count' => $group->count(),
                'revenue' => $group->sum('price'),
            ];
        });

        // Calculate averages
        $avgTransactionsPerDay = $totalTransactions / max($startDate->diffInDays($endDate) + 1, 1);
        $avgRevenuePerDay = $totalRevenue / max($startDate->diffInDays($endDate) + 1, 1);
        $avgDurationPerDay = $totalDuration / max($startDate->diffInDays($endDate) + 1, 1);

        return [
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'totalDuration' => $totalDuration,
            'uniqueUsers' => $uniqueUsers,
            'uniqueClients' => $uniqueClients,
            'transactionTypes' => $transactionTypes,
            'clientTypes' => $clientTypes,
            'topClients' => $topClients,
            'topServices' => $topServices,
            'dailyTrends' => $dailyTrends,
            'weeklyTrends' => $weeklyTrends,
            'statusBreakdown' => $statusBreakdown,
            'chargeBreakdown' => $chargeBreakdown,
            'avgTransactionsPerDay' => $avgTransactionsPerDay,
            'avgRevenuePerDay' => $avgRevenuePerDay,
            'avgDurationPerDay' => $avgDurationPerDay,
            'month' => $month,
            'clientId' => $clientId,
            'serviceId' => $serviceId,
        ];
    }

    /**
     * Export daily report data
     *
     * @param Request $request
     * @param string $format
     * @return array
     */
    public function exportDaily(Request $request, string $format = 'excel'): array
    {
        $reportData = $this->getDailyReportData($request);

        // Prepare data for export
        $exportData = [
            'summary' => [
                'Total Transactions' => $reportData['totalTransactions'],
                'Total Revenue' => $reportData['totalRevenue'],
                'Total Duration' => $reportData['totalDuration'],
                'Unique Users' => $reportData['uniqueUsers'],
                'Unique Clients' => $reportData['uniqueClients'],
            ],
            'transaction_types' => $reportData['transactionTypes'],
            'client_types' => $reportData['clientTypes'],
            'top_clients' => $reportData['topClients'],
            'top_services' => $reportData['topServices'],
            'hourly_trends' => $reportData['hourlyTrends'],
            'status_breakdown' => $reportData['statusBreakdown'],
            'charge_breakdown' => $reportData['chargeBreakdown'],
        ];

        return $exportData;
    }

    /**
     * Export monthly report data
     *
     * @param Request $request
     * @param string $format
     * @return array
     */
    public function exportMonthly(Request $request, string $format = 'excel'): array
    {
        $reportData = $this->getMonthlyReportData($request);

        // Prepare data for export
        $exportData = [
            'summary' => [
                'Total Transactions' => $reportData['totalTransactions'],
                'Total Revenue' => $reportData['totalRevenue'],
                'Total Duration' => $reportData['totalDuration'],
                'Unique Users' => $reportData['uniqueUsers'],
                'Unique Clients' => $reportData['uniqueClients'],
                'Avg Transactions/Day' => $reportData['avgTransactionsPerDay'],
                'Avg Revenue/Day' => $reportData['avgRevenuePerDay'],
                'Avg Duration/Day' => $reportData['avgDurationPerDay'],
            ],
            'transaction_types' => $reportData['transactionTypes'],
            'client_types' => $reportData['clientTypes'],
            'top_clients' => $reportData['topClients'],
            'top_services' => $reportData['topServices'],
            'daily_trends' => $reportData['dailyTrends'],
            'weekly_trends' => $reportData['weeklyTrends'],
            'status_breakdown' => $reportData['statusBreakdown'],
            'charge_breakdown' => $reportData['chargeBreakdown'],
        ];

        return $exportData;
    }

    /**
     * Clear reports cache
     *
     * @param array $params
     * @return void
     */
    public function clearCache(array $params = []): void
    {
        $date = $params['date'] ?? null;
        $month = $params['month'] ?? null;
        $clientId = $params['client_id'] ?? null;
        $serviceId = $params['service_id'] ?? null;

        if ($date) {
            $cacheKey = "daily_report_{$date}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');
            cache()->forget($cacheKey);
        }

        if ($month) {
            $cacheKey = "monthly_report_{$month}_" . ($clientId ?? 'all') . "_" . ($serviceId ?? 'all');
            cache()->forget($cacheKey);
        }

        if (!$date && !$month) {
            // Clear all reports cache patterns
            cache()->flush();
        }
    }
}
