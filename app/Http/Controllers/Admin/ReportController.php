<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Broker;
use App\Models\BrokerM2M;
use App\Models\TradeUser;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Show report dashboard with summary statistics
     */
    public function dashboard()
    {
        // Get user statistics
        $userStats = [
            'total' => TradeUser::count(),
            'active' => TradeUser::where('is_active', 1)->count(),
            'inactive' => TradeUser::where('is_active', 0)->count(),
            'new_today' => TradeUser::whereDate('created_at', Carbon::today())->count(),
            'new_this_week' => TradeUser::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'new_this_month' => TradeUser::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count(),
        ];
        
        // Get transaction statistics
        $transactionStats = [
            'total_deposits' => UserTransaction::where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => UserTransaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'deposits_today' => UserTransaction::where('type', 'deposit')
                                            ->where('status', 'completed')
                                            ->whereDate('created_at', Carbon::today())
                                            ->sum('amount'),
            'withdrawals_today' => UserTransaction::where('type', 'withdrawal')
                                                ->where('status', 'completed')
                                                ->whereDate('created_at', Carbon::today())
                                                ->sum('amount'),
            'pending_withdrawals' => UserTransaction::where('type', 'withdrawal')
                                                ->where('status', 'pending')
                                                ->count(),
        ];
        
        // Get broker statistics
        $brokerStats = [
            'total' => Broker::count(),
            'active' => Broker::where('is_active', 1)->count(),
            'inactive' => Broker::where('is_active', 0)->count(),
        ];
        
        // Get recent broker M2M data
        $recentM2M = BrokerM2M::with('broker')
                            ->orderBy('date', 'desc')
                            ->limit(5)
                            ->get();
        
        return view('admin.reports.dashboard', compact('userStats', 'transactionStats', 'brokerStats', 'recentM2M'));
    }
    
    /**
     * Show user registration report
     */
    public function userRegistration(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        
        // Ensure end date is not before start date
        if ($endDate->lt($startDate)) {
            $endDate = $startDate->copy()->addDays(30);
        }
        
        // Get daily registration counts
        $dailyRegistrations = TradeUser::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Format for chart display
        $dates = $dailyRegistrations->pluck('date')->toArray();
        $counts = $dailyRegistrations->pluck('count')->toArray();
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Viewed user registration report from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return view('admin.reports.user_registration', compact('dailyRegistrations', 'dates', 'counts', 'startDate', 'endDate'));
    }
    
    /**
     * Show transaction report
     */
    public function transactions(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $type = $request->type ?? 'all';
        $status = $request->status ?? 'all';
        
        // Build query
        $query = UserTransaction::with('user')
                                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        
        // Apply filters
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get transactions
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get summary statistics
        $summary = [
            'total_count' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'deposits' => UserTransaction::where('type', 'deposit')
                                        ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                                        ->sum('amount'),
            'withdrawals' => UserTransaction::where('type', 'withdrawal')
                                        ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                                        ->sum('amount'),
            'bonuses' => UserTransaction::where('type', 'bonus')
                                        ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                                        ->sum('amount'),
        ];
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Viewed transaction report from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return view('admin.reports.transactions', compact('transactions', 'summary', 'startDate', 'endDate', 'type', 'status'));
    }
    
    /**
     * Show broker performance report
     */
    public function brokerPerformance(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfMonth() : Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfMonth() : Carbon::now()->endOfMonth();
        $brokerId = $request->broker_id ?? 'all';
        
        // Get all brokers for the filter dropdown
        $brokers = Broker::all();
        
        // Build query
        $query = BrokerM2M::with('broker');
        
        // Apply date filter
        $query->whereBetween('date', [$startDate, $endDate]);
        
        // Apply broker filter
        if ($brokerId !== 'all') {
            $query->where('broker_id', $brokerId);
        }
        
        // Get M2M data
        $m2mData = $query->orderBy('date', 'desc')->get();
        
        // Group by broker and month for chart display
        $chartData = [];
        $months = [];
        
        foreach ($m2mData as $item) {
            $month = Carbon::parse($item->date)->format('M Y');
            $brokerName = $item->broker->name;
            
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
            
            if (!isset($chartData[$brokerName])) {
                $chartData[$brokerName] = [];
            }
            
            $chartData[$brokerName][$month] = $item->profit_loss;
        }
        
        // Sort months chronologically
        usort($months, function($a, $b) {
            return Carbon::parse($a)->timestamp - Carbon::parse($b)->timestamp;
        });
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Viewed broker performance report from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return view('admin.reports.broker_performance', compact('m2mData', 'chartData', 'months', 'brokers', 'startDate', 'endDate', 'brokerId'));
    }
    
    /**
     * Export transaction report to CSV
     */
    public function exportTransactions(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $type = $request->type ?? 'all';
        $status = $request->status ?? 'all';
        
        // Build query
        $query = UserTransaction::with('user')
                                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        
        // Apply filters
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV filename
        $filename = 'transactions_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        // Create CSV file
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'User', 'Type', 'Amount', 'Status', 'Description', 'Created At']);
            
            // Add transaction data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->user ? $transaction->user->name . ' (' . $transaction->user->email . ')' : 'N/A',
                    ucfirst($transaction->type),
                    $transaction->amount,
                    ucfirst($transaction->status),
                    $transaction->description,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        // Log admin action
        AdminLog::create([
            'admin_id' => Session::get('admin_id'),
            'activity' => "Exported transaction report from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return response()->stream($callback, 200, $headers);
    }
}
