<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class RemoveUserExpriedRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-user-expired-register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $numberOfDays = 7;
        $currentDate = Carbon::now()->toDateString();
        DB::table('users')
            ->where('confirm_status', '=', false)
            ->where(DB::raw("DATEDIFF(created_at, '{$currentDate}')"), '=', $numberOfDays)
            ->delete();
    }
}
