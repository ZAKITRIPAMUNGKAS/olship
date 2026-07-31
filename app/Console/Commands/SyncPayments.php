<?php

namespace App\Console\Commands;

use App\Jobs\SyncPendingPayments;
use Illuminate\Console\Command;

class SyncPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pending payments status from gateway';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment sync...');
        SyncPendingPayments::dispatch();
        $this->info('Payment sync job dispatched.');
    }
}
