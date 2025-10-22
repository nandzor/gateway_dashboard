<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\User;
use App\Models\Service;
use App\Models\Client;
use App\Models\Balance;

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

        return [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'avgTransactionValue' => $avgTransactionValue,
        ];
    }
}
