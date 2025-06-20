<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trades', [HomeController::class, 'trades'])->name('trades');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/watchlist', [HomeController::class, 'watchlist'])->name('watchlist');
Route::get('/my-account', [HomeController::class, 'myAccount'])->name('my.account');
Route::get('/deposit-withdraw', [HomeController::class, 'depositWithdraw'])->name('deposit.withdraw');
Route::get('/deposit-request-form', [HomeController::class, 'depositRequestForm'])->name('deposit.request.form');
Route::post('/deposit-request-submit', [HomeController::class, 'depositRequestSubmit'])->name('deposit.submit');
Route::get('/withdrawal_requests', [HomeController::class, 'withdrawalRequests'])->name('withdrawal.requests');

Route::get('/withdrawal-requests-form', [HomeController::class, 'withdrawalRequestsForm'])->name('withdrawal.requests.form');
Route::post('/withdrawal-request-submit', [HomeController::class, 'withdrawalRequestsSubmit'])->name('withdrawal.submit');
Route::get('/login', [HomeController::class, 'login'])->name('login');


    Route::get('/pending', [HomeController::class, 'getPendingTrades'])->name('pending');
    Route::get('/active', [HomeController::class, 'getActiveTrades'])->name('active');
    Route::get('/closed', [HomeController::class, 'getClosedTrades'])->name('closed');
    
    Route::post('/details', [HomeController::class, 'getTradeDetails'])->name('details');
    Route::post('/exit', [HomeController::class, 'exitTrade'])->name('exit');
    Route::post('/bulk-close', [HomeController::class, 'closeBulkTrades'])->name('bulk-close');

// Include Admin Routes
require __DIR__.'/admin.php';
