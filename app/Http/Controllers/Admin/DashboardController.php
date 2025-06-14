<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeUser;
use App\Models\DepositeMaster;
use App\Models\WithdrawlMaster;
use App\Models\Position;
use App\Models\TradeHistory;
use App\Models\Fund;
use App\Models\Notification;
use App\Models\MarketMaster;
use App\Models\ForexOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Admin dashboard with summary statistics
     */
    public function index()
    {
        // Get summary statistics
        $stats = [
            'total_users' => TradeUser::where('IsActive', 1)->count(),
            'active_positions' => Position::where('status', 'open')->count(),
            'pending_deposits' => DepositeMaster::where('Approve_Status', 'PENDING')->count(),
            'pending_withdrawals' => WithdrawlMaster::where('Status', 'PENDING')->count(),
            'total_deposit_amount' => DepositeMaster::where('Approve_Status', 'APPROVED')->sum('Amount'),
            'total_withdrawal_amount' => WithdrawlMaster::where('Status', 'APPROVED')->sum('Amount'),
        ];
        
        // Get recent user registrations
        $recent_users = TradeUser::orderBy('CreatedDate', 'desc')
                               ->take(5)
                               ->get();
        
        // Get recent transactions
        $recent_deposits = DepositeMaster::with('user')
                                      ->orderBy('Timestamp', 'desc')
                                      ->take(5)
                                      ->get();
                                      
        $recent_withdrawals = WithdrawlMaster::with('user')
                                         ->orderBy('Timestamp', 'desc')
                                         ->take(5)
                                         ->get();
        
        return View::make('admin.dashboard', compact('stats', 'recent_users', 'recent_deposits', 'recent_withdrawals'));
    }
    
    public function bankDetails()
    {
        return View::make('admin.bank-details');
    }
    
    public function negativeBalance()
    {
        return View::make('admin.negative-balance');
    }
    
    public function marketWatch()
    {
        return View::make('admin.market-watch');
    }
    
    public function notifications()
    {
        return View::make('admin.notifications');
    }
    
    public function actionLedger()
    {
        return View::make('admin.action-ledger');
    }
    
    public function activePositions()
    {
        return View::make('admin.active-positions');
    }
    
    public function closedPositions()
    {
        return View::make('admin.closed-positions');
    }

    public function users()
    {
        return View::make('admin.users');
    }

    public function trades()
    {
        return View::make('admin.trades');
    }

    public function tradesList()
    {
        return View::make('admin.trades-list');
    }
    
    public function groupTrades()
    {
        return View::make('admin.group-trades');
    }

    public function closedTrades()
    {
        return View::make('admin.closed-trades');
    }

    public function deletedTrades()
    {
        return View::make('admin.deleted-trades');
    }

    public function pendingOrders()
    {
        return View::make('admin.pending-orders');
    }

    public function funds()
    {
        return View::make('admin.funds');
    }
    
    public function scripData()
    {
        return View::make('admin.scrip-data');
    }
    
    public function marketScripts()
    {
        return View::make('admin.market-scripts');
    }
    
    public function accounts()
    {
        return View::make('admin.accounts');
    }
    
    public function socialLinks()
    {
        return View::make('admin.social-links');
    }

    public function createFunds()
    {
        return View::make('admin.create-funds');
    }

    public function createFundsWd()
    {
        return View::make('admin.create-funds-wd');
    }

    public function depositRequests()
    {
        return View::make('admin.deposit-requests');
    }

    public function withdrawalRequests()
    {
        return View::make('admin.withdrawal-requests');
    }

    public function brokers()
    {
        return View::make('admin.brokers');
    }

    public function brokersM2m()
    {
        return View::make('admin.brokers-m2m');
    }

    public function newBroker()
    {
        return View::make('admin.new-broker');
    }

    public function changePassword()
    {
        return View::make('admin.change-password');
    }

    public function changeTransactionPassword()
    {
        return View::make('admin.change-transaction-password');
    }
}
