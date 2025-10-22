<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PriceMasterController;
use App\Http\Controllers\PriceCustomController;
use App\Http\Controllers\CompanyGroupController;
use App\Http\Controllers\CompanyBranchController;
use App\Http\Controllers\DeviceMasterController;
use App\Http\Controllers\ReIdMasterController;
use App\Http\Controllers\CctvLayoutController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\CctvLiveStreamController;
use App\Http\Controllers\EventLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApiCredentialController;
use App\Http\Controllers\BranchEventSettingController;
use App\Http\Controllers\WhatsAppSettingsController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Monitoring routes (no auth required for health checks)
Route::get('/queue-status', [MonitoringController::class, 'queueStatus'])->name('queue.status');
Route::get('/health', [MonitoringController::class, 'health'])->name('health');

// Horizon dashboard (admin only)
Route::get('/horizon', function () {
    return redirect('/horizon/dashboard');
})->middleware('admin');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User CRUD
    Route::resource('users', UserController::class);

    // Client CRUD
    Route::resource('clients', ClientController::class);

    // Additional Management Routes (Placeholder routes for new menu items)
    Route::get('/currencies', function () {
        return view('pages.currencies.index');
    })->name('currencies.index');

    // Histories
    Route::get('/histories', [HistoryController::class, 'index'])->name('histories.index');
    Route::get('/histories/export', [HistoryController::class, 'export'])->name('histories.export');
    Route::get('/histories/{history}', [HistoryController::class, 'show'])->name('histories.show');
    Route::get('/clients/{client}/histories', [HistoryController::class, 'byClient'])->name('histories.by-client');
    Route::get('/services/{service}/histories', [HistoryController::class, 'byService'])->name('histories.by-service');

    Route::get('/price-custom', function () {
        return view('pages.price-custom.index');
    })->name('price-custom.index');

    Route::get('/price-master', function () {
        return view('pages.price-master.index');
    })->name('price-master.index');

    // Service CRUD
    Route::resource('services', ServiceController::class);
    Route::resource('price-masters', PriceMasterController::class);
    Route::resource('price-customs', PriceCustomController::class);
    Route::post('/services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');

    // Company Branches CRUD
    Route::resource('company-branches', CompanyBranchController::class);

    // Device Masters CRUD
    Route::resource('device-masters', DeviceMasterController::class);

    // Person (Re-ID) Management
    Route::get('/re-id-masters', [ReIdMasterController::class, 'index'])->name('re-id-masters.index');
    Route::get('/re-id-masters/export/download', [ReIdMasterController::class, 'export'])->name('re-id-masters.export');
    Route::get('/re-id-masters/{reId}', [ReIdMasterController::class, 'show'])->name('re-id-masters.show');
    Route::patch('/re-id-masters/{reId}', [ReIdMasterController::class, 'update'])->name('re-id-masters.update');

    // Event Logs (Read-only)
    Route::get('/event-logs', [EventLogController::class, 'index'])->name('event-logs.index');
    Route::get('/event-logs/export/download', [EventLogController::class, 'export'])->name('event-logs.export');
    Route::get('/event-logs/{eventLog}', [EventLogController::class, 'show'])->name('event-logs.show');

    // // CCTV Live Stream
    Route::get('/cctv-live-stream', [CctvLiveStreamController::class, 'index'])->name('cctv-live-stream.index');
    Route::get('/api/v1/cctv/streams/{deviceId}', [CctvLiveStreamController::class, 'getStreamUrl'])->name('cctv-live-stream.stream-url');
    Route::put('/api/v1/cctv/layouts/{layoutId}/positions/{positionNumber}', [CctvLiveStreamController::class, 'updatePosition'])->name('cctv-live-stream.update-position');
    Route::get('/api/v1/cctv/branches/{branchId}/devices', [CctvLiveStreamController::class, 'getBranchDevices'])->name('cctv-live-stream.branch-devices');
    Route::post('/api/v1/cctv/screenshots/{deviceId}', [CctvLiveStreamController::class, 'captureScreenshot'])->name('cctv-live-stream.screenshot');
    Route::post('/api/v1/cctv/recordings/{deviceId}', [CctvLiveStreamController::class, 'toggleRecording'])->name('cctv-live-stream.recording');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/export', [ReportController::class, 'exportDashboard'])->name('dashboard.export');
        Route::get('/daily', [ReportsController::class, 'daily'])->name('daily');
        Route::get('/daily/export', [ReportsController::class, 'exportDaily'])->name('daily.export');
        Route::get('/monthly', [ReportsController::class, 'monthly'])->name('monthly');
        Route::get('/monthly/export', [ReportsController::class, 'exportMonthly'])->name('monthly.export');
    });

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/data', [AnalyticsController::class, 'getData'])->name('data');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        // Company Groups CRUD (Admin only)
        Route::resource('company-groups', CompanyGroupController::class);

        // CCTV Layout Management (Admin only)
        Route::resource('cctv-layouts', CctvLayoutController::class);

        // API Credentials Management (Admin only)
        Route::get('api-credentials/{apiCredential}/test', [ApiCredentialController::class, 'test'])->name('api-credentials.test');
        Route::resource('api-credentials', ApiCredentialController::class);

        // Branch Event Settings Management (Admin only)
        Route::resource('branch-event-settings', BranchEventSettingController::class)->except(['create', 'store', 'destroy']);
        Route::post('/branch-event-settings/{branchEventSetting}/toggle', [BranchEventSettingController::class, 'toggle'])->name('branch-event-settings.toggle');

        // WhatsApp Settings Management (Admin only)
        Route::resource('whatsapp-settings', WhatsAppSettingsController::class)->parameters([
            'whatsapp-settings' => 'whatsappSettings'
        ]);
        Route::post('/whatsapp-settings/{whatsappSettings}/set-default', [WhatsAppSettingsController::class, 'setDefault'])->name('whatsapp-settings.set-default');
    });
});
