<?php

use App\Http\Controllers\Dashboard\AlertVariationsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Payment\PaymentsController;
use App\Http\Controllers\Widget\AlertController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/r/{nickname}', [PaymentsController::class, 'show'])->name('payment');
Route::get('/c/{nickname}', [PaymentsController::class, 'show'])->name('payment');
Route::post('/payments/pay', [PaymentsController::class, 'pay']);
Route::get('/payments/completed', [PaymentsController::class, 'completed']);
Route::get('/payments/cancelled', [PaymentsController::class, 'cancelled']);

Route::group(['middleware' => ['auth:sanctum', 'verified'], 'prefix' => 'dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/alert-widget', [AlertVariationsController::class, 'index']);
    Route::post('/alert-variations/store', [AlertVariationsController::class, 'store']);
    Route::post('/alert-variations/edit/{id}', [AlertVariationsController::class, 'update']);
    Route::post('/alert-variations/remove/{id}', [AlertVariationsController::class, 'remove']);
});

Route::group(['prefix' => 'widget'], function () {
    Route::get('/alerts/{token}', [AlertController::class, 'index']);
});

