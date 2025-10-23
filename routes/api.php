<?php

use App\Http\Controllers\Api\V1\BalanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Static Token protected routes for balance management
Route::middleware('static.token')->group(function () {
    // Balance management routes
    Route::post('/balance/check', [BalanceController::class, 'checkBalance'])->name('api.balance.check');
    Route::get('/balance/history', [BalanceController::class, 'getBalanceHistory'])->name('api.balance.history');
    Route::post('/balance/update', [BalanceController::class, 'updateBalance'])->name('api.balance.update');
});
