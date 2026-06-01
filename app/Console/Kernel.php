<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\User\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    // protected $commands = [
    //     Commands\MigrateMsSqlToMySql::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {

   $schedule->call(function () {
        Log::build([
            'driver' => 'single',
            'path'   => storage_path('logs/cron.log'),
        ])->info('⏱️ Scheduler is running every minute');
    })->everyMinute();


        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            
            $today = Carbon::today()->toDateString();
            $homeCont = new HomeController();

            $data = DB::table('forexoptions')
                ->join('marketbidmaster', 'forexoptions.Symbol', '=', 'marketbidmaster.Symbol')
                ->where('marketbidmaster.Isactive', 1)
                ->whereDate('forexoptions.ExpiryDate', $today)
                ->select('marketbidmaster.Pk_id as id')
                ->get();

            foreach ($data as $val) {

                $data = ['exchange_type' => $val->id];
                $request = Request::create('/', 'POST', $data);

                $homeCont->closeBulkTrades($request);
            }
        })->dailyAt('01:00');

        $schedule->call(function () {

            if (function_exists('updateForexOptions')) {
                updateForexOptions();
                Log::info('Forex options updated successfully at ' . now());
            } else {
                Log::error('updateForexOptions helper not found');
            }

        })->dailyAt('01:30')->withoutOverlapping();


        $schedule->call(function () {
                Log::info('Cron working successfully..'. now());
            })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
