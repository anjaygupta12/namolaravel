<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\AuthController;
use App\Models\MarketBidMaster;
use Illuminate\Support\Facades\Auth;

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

Route::get('/check', function() {

    $activeTrades = MarketBidMaster::where('Isactive', 1)
                ->orwhere('Isactive', 0)
                ->where('UserId', Auth::guard('tradeuser')->user()->id)
                ->orderBy('timestamp', 'desc')
                ->get();


}); 
// User Routes

Route::post('/logout', function () {
    Auth::guard('tradeuser')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')->with('success', 'You have been logged out.');
})->name('logout');

Route::get('/', function () {
     
    if (Auth::guard('tradeuser')->check()) {
        return redirect()->route('home'); 
    }
    return redirect('/login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

// Route::middleware(['tradeauth'])->group(function () {
//     Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
//     // add more protected routes here
// });
Route::get('/trades', [HomeController::class, 'trades'])->name('trades');

// Route::get('/trades', [HomeController::class, 'trades'])->name('trades');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/watchlist', [HomeController::class, 'watchlist'])->name('watchlist');
Route::get('/my-account', [HomeController::class, 'myAccount'])->name('my.account');
Route::get('/deposit-withdraw', [HomeController::class, 'depositWithdraw'])->name('deposit.withdraw');
Route::get('/deposit-request-form', [HomeController::class, 'depositRequestForm'])->name('deposit.request.form');
Route::post('/deposit-request-submit', [HomeController::class, 'depositRequestSubmit'])->name('deposit.submit');
Route::get('/withdrawal_requests', [HomeController::class, 'withdrawalRequests'])->name('withdrawal.requests');
Route::post('/user-update-password', [HomeController::class, 'updatePassword'])->name('password.update');

Route::get('/withdrawal-requests-form', [HomeController::class, 'withdrawalRequestsForm'])->name('withdrawal.requests.form');
Route::post('/withdrawal-request-submit', [HomeController::class, 'withdrawalRequestsSubmit'])->name('withdrawal.submit');
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');
Route::post('/register-store', [HomeController::class, 'userRegister'])->name('register.submit');
Route::post('/user-login', [HomeController::class, 'userLogin'])->name('user.login');
Route::get('/getdata', [HomeController::class, 'getData'])->name('getData');
Route::post('/save-transaction', [HomeController::class, 'saveTransaction'])->name('save.transaction');
Route::post('/get/symbol', [HomeController::class, 'getSymbol'])->name('get.symbol');
Route::post('/watchlist-update', [HomeController::class, 'UpdateWatchList'])->name('watchlist.update');

Route::get('/pending', [HomeController::class, 'getPendingTrades'])->name('pending');
Route::get('/active', [HomeController::class, 'getActiveTrades'])->name('active');
Route::get('/closed', [HomeController::class, 'getClosedTrades'])->name('closed');

Route::post('/details', [HomeController::class, 'getTradeDetails'])->name('details');
Route::post('/exit', [HomeController::class, 'exitTrade'])->name('exit');
Route::post('/bulk-close', [HomeController::class, 'closeBulkTrades'])->name('bulk-close');

// Include Admin Routes
require __DIR__ . '/admin.php';
