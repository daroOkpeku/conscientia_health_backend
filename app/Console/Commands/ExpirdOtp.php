<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
class ExpirdOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expird:otp';

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
       

        $user = User::whereNotNull('otp')
            ->where('otp', '!=', '')
            ->where('otp_status', 'nothing')
            ->where('updated_at', '<', Carbon::now()->subMinutes(2))
            ->update(['otp_status' => 'used']);;
    }
}
