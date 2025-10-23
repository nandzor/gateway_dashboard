<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PriceMasterController;
use App\Http\Controllers\PriceCustomController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CompanyGroupController;
use App\Http\Controllers\CompanyBranchController;
use App\Http\Controllers\DeviceMasterController;
use App\Http\Controllers\ReIdMasterController;
use App\Http\Controllers\CctvLayoutController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\CctvLiveStreamController;
use App\Http\Controllers\EventLogController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApiCredentialController;
use App\Http\Controllers\BranchEventSettingController;
use App\Http\Controllers\WhatsAppSettingsController;
use App\Http\Controllers\BalanceTopupController;
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

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // User CRUD
    Route::resource('users', UserController::class);
    // Client CRUD
    Route::resource('clients', ClientController::class);
    // Histories
    Route::get('/histories', [HistoryController::class, 'index'])->name('histories.index');
    Route::get('/histories/export', [HistoryController::class, 'export'])->name('histories.export');
    Route::get('/histories/{history}', [HistoryController::class, 'show'])->name('histories.show');
    Route::get('/clients/{client}/histories', [HistoryController::class, 'byClient'])->name('histories.by-client');
    Route::get('/services/{service}/histories', [HistoryController::class, 'byService'])->name('histories.by-service');
    // Service CRUD
    Route::resource('services', ServiceController::class);
    Route::resource('price-masters', PriceMasterController::class);
    Route::resource('price-customs', PriceCustomController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::post('/services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
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

           // Balance Topups
           Route::resource('balance-topups', BalanceTopupController::class);
           Route::post('/balance-topups/{balanceTopup}/approve', [BalanceTopupController::class, 'approve'])->name('balance-topups.approve');
           Route::post('/balance-topups/{balanceTopup}/reject', [BalanceTopupController::class, 'reject'])->name('balance-topups.reject');
           Route::post('/balance-topups/{balanceTopup}/cancel', [BalanceTopupController::class, 'cancel'])->name('balance-topups.cancel');

       });
