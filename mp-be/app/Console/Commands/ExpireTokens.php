<?php

namespace App\Console\Commands;

use App\Models\AuthResetPassword;
use Illuminate\Console\Command;

class ExpireTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-tokens';

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
        $expiryTime = now()->subMinutes(2);
        
        AuthResetPassword::where('is_active', 1)
            ->where('is_expired', 1)
            ->where('created_date', '<=', $expiryTime)
            ->update([
                'is_active' => 0,
                'is_expired' => 0
            ]);

        $this->info('Expired tokens updated successfully.');
    }
}
