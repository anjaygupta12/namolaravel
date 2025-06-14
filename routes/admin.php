<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrokerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Middleware\AdminAuth;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

// Authentication Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Guest routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    
    // Transaction password verification
    Route::get('transaction-password', [AuthController::class, 'showTransactionPasswordForm'])->name('transaction.password');
    Route::post('transaction-password', [AuthController::class, 'verifyTransactionPassword'])->name('transaction.password.submit');
    
    // Protected routes
    Route::group(['middleware' => 'web'], function () {
        // Dashboard
        Route::get('/', function () {
            return Redirect::route('admin.dashboard');
        });
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Market Watch
        Route::get('market-watch', [AdminController::class, 'marketWatch'])->name('market-watch');
        
        // Admin profile
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::get('change-password', [AdminController::class, 'changePassword'])->name('change-password');
        Route::get('change-transaction-password', [AdminController::class, 'changeTransactionPassword'])->name('change-transaction-password');
        Route::post('profile/update-password', [AdminController::class, 'updatePassword'])->name('profile-update-password');
        Route::post('profile/update-transaction-password', [AdminController::class, 'updateTransactionPassword'])
            ->name('update-transaction-password');
            
        // User Management
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('users-create');
        Route::post('users/store', [AdminController::class, 'storeUser'])->name('users-store');
        Route::get('users/view/{id}', [AdminController::class, 'viewUser'])->name('users-view');
        Route::get('users/edit/{id}', [AdminController::class, 'editUser'])->name('users-edit');
        Route::post('users/update/{id}', [AdminController::class, 'updateUser'])->name('users-update');
        Route::get('users/copy/{id}', [AdminController::class, 'copyUser'])->name('users-copy');
        Route::post('users/toggle-status/{id}', [AdminController::class, 'toggleUserStatus'])->name('users-toggle-status');
        Route::delete('users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users-delete');
        Route::get('comex-margins/{id}', [AdminController::class, 'comexMargins'])->name('comex-margins');
        Route::get('wf-status/{id}', [AdminController::class, 'wfStatus'])->name('wf-status');
        
        // Social links
        Route::get('social-links', [AdminController::class, 'socialLinks'])->name('social-links');
        Route::post('social-links', [AdminController::class, 'updateSocialLinks'])->name('social-links.update');
        
        // Notifications
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
        Route::post('notifications', [AdminController::class, 'storeNotification'])->name('notifications-store');
        Route::delete('notifications/{id}', [AdminController::class, 'deleteNotification'])->name('notifications-delete');
        
        // Action ledger
        Route::get('action-ledger', [AdminController::class, 'actionLedger'])->name('action-ledger');
        
        // Brokers
        Route::get('brokers', [BrokerController::class, 'index'])->name('brokers');
        Route::get('brokers/create', [BrokerController::class, 'create'])->name('brokers-create');
        Route::post('brokers', [BrokerController::class, 'store'])->name('brokers-store');
        Route::get('brokers/{id}/edit', [BrokerController::class, 'edit'])->name('brokers-edit');
        Route::put('brokers/{id}', [BrokerController::class, 'update'])->name('brokers-update');
        Route::delete('brokers/{id}', [BrokerController::class, 'destroy'])->name('brokers-destroy');
        Route::post('brokers/{id}/toggle-status', [BrokerController::class, 'toggleStatus'])->name('brokers-toggle-status');
        
        // Broker M2M
        Route::get('brokers/{id}/m2m', [BrokerController::class, 'showM2M'])->name('brokers-m2m');
        Route::post('brokers/{id}/m2m', [BrokerController::class, 'storeM2M'])->name('brokers-m2m-store');
        Route::get('brokers/{brokerId}/m2m/{id}/edit', [BrokerController::class, 'editM2M'])->name('brokers-m2m-edit');
        Route::put('brokers/{brokerId}/m2m/{id}', [BrokerController::class, 'updateM2M'])->name('brokers-m2m-update');
        Route::delete('brokers/{brokerId}/m2m/{id}', [BrokerController::class, 'destroyM2M'])->name('brokers-m2m-destroy');
        
        // Users - Using AdminController instead of UserController
        // Routes already defined above
        Route::get('users/{id}/transactions', [UserController::class, 'transactions'])->name('users-transactions');
        Route::get('users/{id}/add-balance', [UserController::class, 'showAddBalance'])->name('users-add-balance');
        Route::post('users/{id}/add-balance', [UserController::class, 'addBalance'])->name('users-add-balance-submit');
        
        // Settings
        Route::prefix('settings')->name('settings-')->group(function () {
            Route::get('general', [SettingsController::class, 'general'])->name('general');
            Route::post('general', [SettingsController::class, 'updateGeneral'])->name('general-update');
            
            Route::get('trading', [SettingsController::class, 'trading'])->name('trading');
            Route::post('trading', [SettingsController::class, 'updateTrading'])->name('trading-update');
            
            Route::get('payment', [SettingsController::class, 'payment'])->name('payment');
            Route::post('payment', [SettingsController::class, 'updatePayment'])->name('payment-update');
            
            Route::get('notification', [SettingsController::class, 'notification'])->name('notification');
            Route::post('notification', [SettingsController::class, 'updateNotification'])->name('notification-update');
        });
        
        // Bank Details
        Route::get('bank-details', [AdminController::class, 'bankDetails'])->name('bank-details');
        Route::get('bank-details-edit/{id}', [AdminController::class, 'bankDetailsEdit'])->name('bank-details-edit');

        Route::post('bank-details', [AdminController::class, 'updateBankDetails'])->name('update-bank-details');
        
        // Negative Balance
        Route::get('negative-balance', [AdminController::class, 'negativeBalance'])->name('negative-balance');
        
        // Market Watch
        Route::get('market-watch', [AdminController::class, 'marketWatch'])->name('market-watch');
        
        // Active Positions
        Route::get('active-positions', [AdminController::class, 'activePositions'])->name('active-positions');
        
        // Closed Positions
        Route::get('closed-positions', [AdminController::class, 'closedPositions'])->name('closed-positions');
        
        // Trades
        Route::get('trades', [AdminController::class, 'trades'])->name('trades');
        Route::get('trades-list', [AdminController::class, 'tradesList'])->name('trades-list');
        Route::get('group-trades', [AdminController::class, 'groupTrades'])->name('group-trades');
        Route::get('closed-trades', [AdminController::class, 'closedTrades'])->name('closed-trades');
        Route::get('deleted-trades', [AdminController::class, 'deletedTrades'])->name('deleted-trades');
        Route::get('pending-orders', [AdminController::class, 'pendingOrders'])->name('pending-orders');
        
        // Funds
        Route::get('funds', [AdminController::class, 'funds'])->name('funds-wds');
        Route::get('create-funds', [AdminController::class, 'createFunds'])->name('create-funds');
        Route::get('create-funds-wd', [AdminController::class, 'createFundsWd'])->name('create-funds-wd');
        Route::get('deposit-requests', [AdminController::class, 'depositRequests'])->name('deposit-requests');
        Route::post('deposit-status', [AdminController::class, 'handleDeposit'])->name('deposit-status');
        Route::get('withdrawal-requests', [AdminController::class, 'withdrawalRequests'])->name('withdrawal-requests');
        
        // Users
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('create-user', [AdminController::class, 'createUsers'])->name('user-create');
        Route::get('users/view/{id}', [UserController::class, 'viewUser'])->name('users-view');
        Route::get('mcxusers-views/{id}', [UserController::class, 'mcxUsersViews'])->name('mcxusers-views');
        
        // Trades routes
        Route::get('trades', [AdminController::class, 'trades'])->name('trades');
        Route::get('trades-list', [AdminController::class, 'tradesList'])->name('trades-list');
        Route::get('group-trades', [AdminController::class, 'groupTrades'])->name('group-trades');
        Route::get('closed-trades', [AdminController::class, 'closedTrades'])->name('closed-trades');
        Route::get('deleted-trades', [AdminController::class, 'deletedTrades'])->name('deleted-trades');
        Route::get('pending-orders', [AdminController::class, 'pendingOrders'])->name('pending-orders');
        
        
        // Action Ledger
        Route::get('action-ledger', [AdminController::class, 'actionLedger'])->name('action-ledger');
        
        // Deposit and Withdrawal Requests
        Route::get('deposit-requests', [AdminController::class, 'depositRequests'])->name('deposit-requests');
        Route::get('withdrawal-requests', [AdminController::class, 'withdrawalRequests'])->name('withdrawal-requests');
        
        // Accounts Management
        Route::get('accounts', [AdminController::class, 'accounts'])->name('accounts');
        
        // Market Scripts and Scrip Data
        Route::get('market-scripts', [AdminController::class, 'marketScripts'])->name('market-scripts');
        Route::get('scrip-data', [AdminController::class, 'scripData'])->name('scrip-data');
        
        // Reports
        Route::prefix('reports')->name('reports-')->group(function () {
            Route::get('dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
            Route::get('user-registration', [ReportController::class, 'userRegistration'])->name('user-registration');
            Route::get('transactions', [ReportController::class, 'transactions'])->name('transactions');
            Route::get('broker-performance', [ReportController::class, 'brokerPerformance'])->name('broker-performance');
            Route::get('export/transactions', [ReportController::class, 'exportTransactions'])->name('export-transactions');
        });
        
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
