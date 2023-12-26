<?php

namespace Xcelerate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Xcelerate\Models\BatchUpload;

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
    protected $description = 'Take data out of batch_uploads and place it in the appropriate table.';

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
        $batchMessage = 'The batch data has already been processed.';
        $return =  true;

        $batchUploads = BatchUpload::whereNotIn('status', ['processed'])->get();
        if(count($batchUploads) > 0){
            foreach($batchUploads as $eachBatch){
                $eachBatch->status = 'processing';
                $eachBatch->update();

                $batchUploadRecords = $eachBatch->batchUploadRecords()->get();
                if(isset($batchUploadRecords)){
                    DB::table($eachBatch->model)
                        ->where('company_id', $eachBatch->company_id)
                        ->update([
                            'is_deleted' => "1",
                            'is_sync' => false
                        ]);

                    foreach($batchUploadRecords as $eachBatchUploadRecord){
                        if(in_array($eachBatchUploadRecord->status, [NULL, 'failed', 'created'])){
                            $eachBatchUploadRecord->importRecord($eachBatch->company_id, strtolower($eachBatch->model), $eachBatchUploadRecord->row_data);
                        }
                    }
                } 

                $eachBatch->status = 'processed';
                $eachBatch->completed_time = date("Y-m-d H:i:s");
                $eachBatch->update();
            }

            $batchMessage = 'Batch data was successfully processed.';
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
