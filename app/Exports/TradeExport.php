<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\Models\marketbidmaster;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class TradeExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $trades = marketbidmaster::whereDate('created_at', '>=', $this->from)
                    ->whereDate('created_at', '<=', $this->to)
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('admin.exports.trades-full', compact('trades'));
    }
}
