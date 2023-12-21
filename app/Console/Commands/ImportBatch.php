<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Xcelerate\Models\BatchUpload;
use Xcelerate\Models\BatchUploadRecord;

class ImportBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from batch_uploads and insert it into respective table';

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
        $batchMessage = 'Batch Data is already processed.';
        $return =  true;

        $batchUploads = BatchUpload::whereNotIn('status', ['processed'])->get();
        if(count($batchUploads) > 0){
            foreach($batchUploads as $eachBatch){
                $eachBatch->status = 'processing';
                $eachBatch->update();

                $batchUploadRecords = $eachBatch->batchUploadRecords()->get();
                if($batchUploadRecords){
                    foreach($batchUploadRecords as $eachBatchUploadRecord){
                        if(in_array($eachBatchUploadRecord->status, [NULL, 'failed'])){
                            $eachBatchUploadRecord->importRecord($eachBatch->company_id, strtolower($eachBatch->model), $eachBatchUploadRecord->row_data);
                        }
                    }
                } 

                $eachBatch->status = 'processed';
                $eachBatch->completed_time = date("Y-m-d H:i:s");
                $eachBatch->update();
            }

            $batchMessage = 'Batch Data processed successfully.';
        }

        if($return){
            $this->info($batchMessage);
        }
        else{
            $this->info($batchMessage);
        }
        exit;
    }
}
