<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\MarketBidMaster;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class TradeExport implements FromView
{
    protected $from;
    protected $to;
    protected  $userID;
    public function __construct($from, $to, $userID)
    {
        $this->from = $from;
        $this->to = $to;
        $this->userID = $userID;
    }

    public function view(): View
    {
        $trades = MarketBidMaster::where('UserId',$this->userID)
        ->whereDate('created_at', '>=', $this->from)
                    ->whereDate('created_at', '<=', $this->to)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.exports.trades-full', compact('trades'));
    }
}
