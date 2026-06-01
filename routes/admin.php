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
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\Admin\DataImportController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

// Authentication Routes
Route::get('/admin/data-import-auto', [DataImportController::class, 'importDataAuto'])->name('admin.data-import-auto');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Data Import Routes
        // Data Import Routes
        Route::get('delete-calendar-holiday/{id}', [AdminController::class, 'deleteHoliday'])
     ->name('calendar.holiday.delete');

        Route::get('set-calender', [AdminController::class, 'setCalender'])->name('set-calender');
        Route::post('store-calender', [AdminController::class, 'storeCalender'])->name('calendar.store');

        // Route::get('/data-import-auto', [DataImportController::class, 'importDataAuto'])->name('data-import-auto');

        Route::get('/data-import', [DataImportController::class, 'index'])->name('data-import');
        Route::post('/data-import', [DataImportController::class, 'importDataMunu'])->name('data-import');

    // Guest routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    // Transaction password verification
    Route::get('transaction-password', [AuthController::class, 'showTransactionPasswordForm'])->name('transaction.password');
    Route::post('transaction-password', [AuthController::class, 'verifyTransactionPassword'])->name('transaction.password.submit');

    // Protected routes
    Route::group(['middleware' => 'web'], function () {
        // Dashboard
     Route::get('/update-fyers', function () {
            // updateForexOptions();
            updateFyersTokens();
            dd('updated..');

        });
        Route::get('/', function () {
    //                     $symbols = [
    //                 'NSE:SBIN-EQ',
    //                 'MCX:CRUDEOILM25JULFUT',
    //                 'MCX:SILVERM25AUGFUT'
    //             ];

    // $quotes = fetchFyersQuotes($symbols);
    // dd($quotes);
            return Redirect::route('admin.dashboard');
        });

        Route::get('role-permisstion', [RolePermissionController::class, 'index'])->name('roles.index');
        // Route::post('role-store', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        // Route::post('permission-store', [RolePermissionController::class, 'storePermission'])->name('permissions.store');

    // Route::get('/roles', [RolePermissionController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'updatePermissions'])->name('roles.update-permissions');

    Route::resource('admin-users', AdminLoginController::class)->except(['show']);
    Route::put('admin-users/update/{id}', [AdminLoginController::class,'update'])->name('admin.users.update');

    // Permission Routes
    Route::post('/permissions', [RolePermissionController::class, 'storePermission'])->name('permissions.store');


        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('m2m/{any}/{id}', [AdminController::class, 'brokerM2m'])->name('brokerm2m');

        // Market Watch
        Route::get('market-watch', [AdminController::class, 'marketWatch'])->name('market-watch');
        Route::get('banned-status', [AdminController::class, 'bannedStatus'])->name('admin.banned.status');

        // Admin profile
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
        Route::get('change-password', [AdminController::class, 'changePassword'])->name('change-password');
        Route::get('change-transaction-password', [AdminController::class, 'changeTransactionPassword'])->name('change-transaction-password');
        Route::post('profile/update-password', [AdminController::class, 'updatePassword'])->name('profile-update-password');
        Route::post('profile/update-transaction-password', [AdminController::class, 'updateTransactionPassword'])
            ->name('update-transaction-password');
        Route::get('change-auth-code', [AdminController::class, 'changeAuthCode'])->name('change-authcode');
        Route::post('update-auth-code', [AdminController::class, 'updateAuthCode'])->name('change-authcode.update');
                Route::post('profile/update-user-password', [AdminController::class, 'updateUserPassword'])
            ->name('update-user-password');

        // User Managementf
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('users-create');
        Route::post('users/store', [AdminController::class, 'storeUser'])->name('users-store');
        Route::get('users/view/{id}', [AdminController::class, 'viewUser'])->name('users-view');
        Route::get('users/edit/{id}', [AdminController::class, 'editUser'])->name('users-edit');
        Route::get('users/reset/{id}', [AdminController::class, 'resetAccount'])->name('users-reset');
        Route::get('recalculate-brokerage/{id}', [AdminController::class, 'recalculateBrokerage'])->name('recalculate-brokerage');
        Route::get('/live-prices/{id}', [AdminController::class, 'getLivePrices']);
        Route::get('/dashboard-live-pl/{any}/{id}', [AdminController::class, 'dashboardLivePl']);

        Route::get('trade-view/{id}/{userid}', [AdminController::class, 'tradeView'])->name('trade-view');
        Route::get('update-trade/{id}/{userid}', [AdminController::class, 'EditTrade'])->name('trade-edit');
        Route::get('delete-trade-confirm/{id}/{userid}', [AdminController::class, 'DeleteTradeConfirm'])->name('trade-delete-confirm');
        Route::get('close-trade-confirm/{id}/{userid}', [AdminController::class, 'closedTradesConfirm'])->name('close-trade-confirm');
        Route::post('close-trade-confirm/{id}/{userid}', [AdminController::class, 'closedTradeAdmin'])->name('close-trade-admin');

        Route::get('delete-user-confirm/{id}', [AdminController::class, 'deleteUserConfirm'])->name('delete-user-confirm');
        Route::get('reset-account-confirm/{id}', [AdminController::class, 'resetAcountConfirm'])->name('reset-coount-confirm');

        Route::post('delete-trade/{id}/{userid}', [AdminController::class, 'DeleteTrade'])->name('trade-delete');
        Route::get('restore-trade/{id}/{userid}', [AdminController::class, 'RestoreTrade'])->name('trade-restore');
        Route::post('update-trade', [AdminController::class, 'updateTrade'])->name('trade-update');
        Route::post('restore-trade-update', [AdminController::class, 'RestoreTradeUpdate'])->name('trade-restore-update');


        Route::post('/trades/export', [AdminController::class, 'exportExcel'])->name('trades.export');
        Route::post('/trades/pdf/{id}', [AdminController::class, 'exportPdf'])->name('trades.pdf');
        Route::post('/funds/export', [AdminController::class, 'export'])->name('funds.export');

        Route::put('users/update/{id}', [AdminController::class, 'updateUser'])->name('users-update');
        Route::get('users/copy/{id}', [AdminController::class, 'copyUser'])->name('users-copy');
        Route::post('users/toggle-status/{id}', [AdminController::class, 'toggleUserStatus'])->name('users-toggle-status');
        Route::delete('users/delete/{id}', [AdminController::class, 'deleteUser'])->name('users-delete');
        Route::get('comex-margins/{id}', [AdminController::class, 'comexMargins'])->name('comex-margins');
        Route::get('wf-status/{id}', [AdminController::class, 'wfStatus'])->name('wf-status');
        Route::post('comex-margins/{id}', [AdminController::class, 'updateComexMargins'])->name('comex-margins.update');

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
        Route::get('brokers/{id}/copy', [BrokerController::class, 'copy'])->name('brokers-copy');
        Route::put('brokers/{id}', [BrokerController::class, 'update'])->name('brokers-update');
        Route::delete('brokers/{id}', [BrokerController::class, 'destroy'])->name('brokers-destroy');
        Route::get('brokers/{id}', [BrokerController::class, 'toggleStatus'])->name('brokers-toggle-status');
        
        Route::get('sub-brokers-users/{id}', [AdminController::class, 'subBrokerUsers'])->name('sub.broker.users');

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
        Route::get('active-users/{id}', [AdminController::class, 'activeUsers'])->name('active-users');
        Route::get('closed-users/{id}', [AdminController::class, 'closedUsers'])->name('closed-users');

        // Closed Positions
        Route::get('closed-positions', [AdminController::class, 'closedPositions'])->name('closed-positions');

        // Trades
        Route::get('trades', [AdminController::class, 'trades'])->name('trades');
        Route::get('trade-create', [AdminController::class, 'tradeCreate'])->name('trade.create');
        Route::post('trade-store', [AdminController::class, 'storeOrUpdate'])->name('trades.save');
        Route::get('trade-edit/{id}', [AdminController::class, 'tradesEdit'])->name('trade.edit');

        Route::get('trades-list', [AdminController::class, 'tradesList'])->name('trades-list');
        Route::get('group-trades', [AdminController::class, 'groupTrades'])->name('group-trades');
        Route::get('closed-trades', [AdminController::class, 'closedTrades'])->name('closed-trades');
        Route::get('deleted-trades', [AdminController::class, 'deletedTrades'])->name('deleted-trades');
        Route::get('pending-orders', [AdminController::class, 'pendingOrders'])->name('pending-orders');
        Route::get('pending-orders-create', [AdminController::class, 'createPendingOrder'])->name('pending-orders-create');
        Route::post('restore-trade-create', [AdminController::class, 'createPendingTrade'])->name('pending-trade-create');

        // Fundsf
        Route::get('funds', [AdminController::class, 'funds'])->name('funds-wds');
        Route::get('funds-report', [AdminController::class, 'fundsReport'])->name('funds-report');
        Route::post('funds-report-store', [AdminController::class, 'fundsStore'])->name('funds.store');
        Route::get('create-funds/{id}', [AdminController::class, 'createFunds'])->name('create-funds');
        Route::get('create-funds-wd/{id}', [AdminController::class, 'createFundsWd'])->name('create-funds-wd');
        Route::get('payment-confirm/{id}', [AdminController::class, 'paymentConfirm'])->name('payment-confirm');

        Route::get('deposit-requests', [AdminController::class, 'depositRequests'])->name('deposit-requests');
        Route::post('deposit-status', [AdminController::class, 'handleDeposit'])->name('deposit-status');
        Route::get('withdrawal-requests', [AdminController::class, 'withdrawalRequests'])->name('withdrawal-requests');
         Route::post('fund-withdrawal', [AdminController::class, 'fundWithdrawal'])->name('funds.withdraw');
        // Users
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('create-user', [AdminController::class, 'createUsers'])->name('user-create');
        Route::post('store-trade-user', [AdminController::class, 'storeTradeUser'])->name('store.trade.user');
        // Route::get('users/view/{id}', [UserController::class, 'viewUser'])->name('users-view');
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
        // Route::get('deposit-requests', [AdminController::class, 'depositRequests'])->name('deposit-requests');
        Route::get('withdrawal-requests', [AdminController::class, 'withdrawalRequests'])->name('withdrawal-requests');

        // Accounts Management
        Route::get('accounts', [AdminController::class, 'accounts'])->name('accounts');
        Route::get('accounts/{id}', [AdminController::class, 'subAaccounts'])->name('sub.accounts');

        // Market Scripts and Scrip Data
        Route::get('market-scripts', [AdminController::class, 'marketScripts'])->name('market-scripts');
        Route::get('scrip-data', [AdminController::class, 'scripData'])->name('scrip-data');
        Route::get('script-status/{id}', [AdminController::class, 'scriptStatus'])->name('acript.status');
        Route::get('script-edit/{id}', [AdminController::class, 'editScript'])->name('script.edit');
        Route::get('script-delete/{id}', [AdminController::class, 'deleteScript'])->name('script.delete');
        Route::post('script-update', [AdminController::class, 'updateScript'])->name('script.update');
        Route::post('scrip-data/getdata', [AdminController::class, 'getScripData'])->name('get.scrip.data');
        // manage lot size
        Route::get('manage/lot-size', [AdminController::class, 'lotSize'])->name('lot.size');
        Route::post('lot-size/store', [AdminController::class, 'storeLotSize'])->name('lot-size.store');
        Route::get('lot-size/edit/{id}', [AdminController::class, 'editLotSize'])->name('lot-size.edit');
        Route::post('lot-size/update/{id}', [AdminController::class, 'updateLotSize'])->name('lot-size.update');
            // Reports
            // manage forex options
        Route::get('manage/forx-options', [AdminController::class, 'forexOptions'])->name('forex.option');
         Route::post('update/forx-options', [AdminController::class, 'forexOptionUpdate'])->name('forex.option.update');

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
