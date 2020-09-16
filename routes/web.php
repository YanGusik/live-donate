<?php

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


Route::get('/widget/alerts/{token}', [AlertController::class, 'index']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
