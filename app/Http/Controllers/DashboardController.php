<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\User;
use App\Models\Service;
use App\Models\Client;
use App\Models\Balance;
use App\Models\History;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $userService;

    // Constants for limits
    private const RECENT_LIMIT = 10;
    private const TOP_LIMIT = 5;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display dashboard with context tables for users, services, clients, and history
     */
    public function index()
    {
        $dashboardData = $this->getDashboardData();

        return view('dashboard.index', $dashboardData);
    }

    /**
     * Get all dashboard data focused on context tables
     */
    private function getDashboardData(): array
    {
        return [
            // Core statistics
            ...$this->getCoreStatistics(),

            // Context tables data
            ...$this->getContextTablesData(),

            // Service-client relationships
            ...$this->getRelationshipData(),

            // Display flags
            ...$this->getDisplayFlags(),

            // Additional data for enhanced dashboard
            ...$this->getEnhancedDashboardData(),
        ];
    }

    /**
     * Get core system statistics for context tables
     */
    private function getCoreStatistics(): array
    {
        // User statistics
        $userStats = User::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as admin,
            SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as operator,
            SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as viewer
        ', ['admin', 'operator', 'viewer'])->first();

        // Service statistics
        $serviceStats = Service::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN type = 1 THEN 1 ELSE 0 END) as internal,
            SUM(CASE WHEN type = 2 THEN 1 ELSE 0 END) as external
        ')->first();

        // Client statistics
        $clientStats = Client::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN is_staging = 1 THEN 1 ELSE 0 END) as staging,
            SUM(CASE WHEN is_staging = 0 THEN 1 ELSE 0 END) as production
        ')->first();

        return [
            // User statistics
            'totalUsers' => $userStats->total,
            'activeUsers' => $userStats->active,
            'inactiveUsers' => $userStats->inactive,
            'adminUsers' => $userStats->admin,
            'operatorUsers' => $userStats->operator,
            'viewerUsers' => $userStats->viewer,

            // Service statistics
            'totalServices' => $serviceStats->total,
            'activeServices' => $serviceStats->active,
            'inactiveServices' => $serviceStats->inactive,
            'internalServices' => $serviceStats->internal,
            'externalServices' => $serviceStats->external,

            // Client statistics
            'totalClients' => $clientStats->total,
            'activeClients' => $clientStats->active,
            'inactiveClients' => $clientStats->inactive,
            'stagingClients' => $clientStats->staging,
            'productionClients' => $clientStats->production,
        ];
    }

    /**
     * Get context tables data for users, services, clients, and history
     */
    private function getContextTablesData(): array
    {
        return [
            // Recent users context table
            'recentUsers' => User::select(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
                ->whereNotNull('created_at')
                ->latest('created_at')
                ->limit(self::RECENT_LIMIT)
                ->get(),

            // Recent services context table
            'recentServices' => Service::select(['id', 'name', 'type', 'is_active', 'created_at'])
                ->whereNotNull('created_at')
                ->latest('created_at')
                ->limit(self::RECENT_LIMIT)
                ->get(),

            // Recent clients context table
            'recentClients' => Client::select(['id', 'client_name', 'type', 'is_active', 'is_staging', 'created_at'])
                ->whereNotNull('created_at')
                ->latest('created_at')
                ->limit(self::RECENT_LIMIT)
                ->get(),

            // Recent history/balances context table
            'recentBalances' => Balance::with('client:id,client_name')
                ->select(['id', 'client_id', 'balance', 'quota', 'created_at'])
                ->whereNotNull('created_at')
                ->latest('created_at')
                ->limit(self::RECENT_LIMIT)
                ->get(),
        ];
    }

    /**
     * Get service-client relationship data for context analysis
     */
    private function getRelationshipData(): array
    {
        return [
            // Top services by client count
            'topServices' => Service::withCount('clients')
                ->orderBy('clients_count', 'desc')
                ->limit(self::TOP_LIMIT)
                ->get(['id', 'name', 'type', 'is_active']),

            // Top clients by service count
            'topClients' => Client::withCount('services')
                ->orderBy('services_count', 'desc')
                ->limit(self::TOP_LIMIT)
                ->get(['id', 'client_name', 'is_staging', 'is_active']),
        ];
    }

    /**
     * Get display flags for conditional rendering of context tables
     */
    private function getDisplayFlags(): array
    {
        return [
            'hasRecentUsers' => true,      // Will be calculated in view
            'hasRecentServices' => true,   // Will be calculated in view
            'hasRecentClients' => true,    // Will be calculated in view
            'hasRecentBalances' => true,   // Will be calculated in view
        ];
    }

    /**
     * Get context table statistics as JSON (for AJAX requests)
     */
    public function getContextStats()
    {
        $stats = $this->getCoreStatistics();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent context data as JSON (for AJAX requests)
     */
    public function getRecentContext()
    {
        $contextData = $this->getContextTablesData();

        return response()->json([
            'success' => true,
            'data' => $contextData
        ]);
    }

    /**
     * Get service-client relationships as JSON (for AJAX requests)
     */
    public function getRelationships()
    {
        $relationships = $this->getRelationshipData();

        return response()->json([
            'success' => true,
            'data' => $relationships
        ]);
    }

    /**
     * Get enhanced dashboard data for better user experience
     */
    private function getEnhancedDashboardData(): array
    {
        // Calculate revenue statistics
        $totalRevenue = Balance::sum('balance') ?? 0;
        $totalTransactions = Balance::count();
        $avgTransactionValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Get 7-day histories data for charts
        $historiesData = $this->getHistoriesChartData();

        return [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'avgTransactionValue' => $avgTransactionValue,
            'historiesChartData' => $historiesData,
        ];
    }

    /**
     * Get 7-day histories data for charts
     */
    private function getHistoriesChartData(): array
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(6);

        // Get daily transaction counts
        $dailyTransactions = History::selectRaw('DATE(trx_date) as date, COUNT(*) as count')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Get daily revenue
        $dailyRevenue = History::selectRaw('DATE(trx_date) as date, SUM(price) as revenue')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Get daily success rate
        $dailySuccess = History::selectRaw('DATE(trx_date) as date,
            SUM(CASE WHEN status = \'OK\' THEN 1 ELSE 0 END) as success_count,
            COUNT(*) as total_count')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Get top services for the period
        $topServices = History::with('service:id,name')
            ->selectRaw('module_id, COUNT(*) as usage_count, SUM(price) as revenue')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('module_id')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        // Get top clients for the period
        $topClients = History::with('client:id,client_name')
            ->selectRaw('client_id, COUNT(*) as transaction_count, SUM(price) as revenue')
            ->whereBetween('trx_date', [$startDate, $endDate])
            ->groupBy('client_id')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();

        // Prepare chart data
        $chartLabels = [];
        $chartTransactionData = [];
        $chartRevenueData = [];
        $chartSuccessRateData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('M d');

            $chartTransactionData[] = $dailyTransactions->get($dateString, (object)['count' => 0])->count;
            $chartRevenueData[] = $dailyRevenue->get($dateString, (object)['revenue' => 0])->revenue;

            $successData = $dailySuccess->get($dateString, (object)['success_count' => 0, 'total_count' => 0]);
            $successRate = $successData->total_count > 0 ? ($successData->success_count / $successData->total_count) * 100 : 0;
            $chartSuccessRateData[] = round($successRate, 1);
        }

        // Calculate period statistics
        $totalPeriodTransactions = $dailyTransactions->sum('count');
        $totalPeriodRevenue = $dailyRevenue->sum('revenue');
        $avgDailyTransactions = $totalPeriodTransactions / 7;
        $avgDailyRevenue = $totalPeriodRevenue / 7;

        return [
            'labels' => $chartLabels,
            'transactions' => $chartTransactionData,
            'revenue' => $chartRevenueData,
            'successRate' => $chartSuccessRateData,
            'topServices' => $topServices,
            'topClients' => $topClients,
            'statistics' => [
                'totalTransactions' => $totalPeriodTransactions,
                'totalRevenue' => $totalPeriodRevenue,
                'avgDailyTransactions' => round($avgDailyTransactions, 1),
                'avgDailyRevenue' => round($avgDailyRevenue, 2),
                'period' => $startDate->format('M d') . ' - ' . $endDate->format('M d'),
            ],
        ];
    }
}
