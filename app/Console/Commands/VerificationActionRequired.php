<?php

namespace App\Console\Commands;

use App\Models\BatchLog;
use App\Services\CommonBatchService;
use Illuminate\Console\Command;

class VerificationActionRequired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'VerificationActionRequired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notice to the client for Email Verification Process.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        echo "*******Start time************* " .date('Y-m-d h:i:s'); 
        echo "\n"; 
        $id = BatchLog::batchStarted('VerificationActionRequired');
        CommonBatchService::sendVerificationActionEmail();
        BatchLog::batchEnded($id,'VerificationActionRequired');
        echo "**************Completed Time***************************** " .date('Y-m-d h:i:s');
        echo "\n"; 
    }
}
