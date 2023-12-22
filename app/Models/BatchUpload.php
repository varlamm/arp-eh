<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Xcelerate\Models\BatchUploadRecord;

class BatchUpload extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function batchUploadRecords(){
        return $this->hasMany(BatchUploadRecord::class, 'batch_id');
    }
}
