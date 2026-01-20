<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshShiprocketToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature = 'shiprocket:token-refresh';

    protected $description = 'Proactively refreshes Shiprocket API token if expiring soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking Shiprocket token...');

        $service = new \App\Services\ShiprocketService();
        $token = $service->getToken();

        if ($token) {
            $this->info('Shiprocket token is valid.');
        } else {
            $this->error('Failed to get or refresh Shiprocket token.');
        }
    }
}
