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
Route::get('/withdrawal-requests-form', [HomeController::class, 'withdrawalRequestsForm'])->name('withdrawal.requests.form');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Include Admin Routes
require __DIR__.'/admin.php';
