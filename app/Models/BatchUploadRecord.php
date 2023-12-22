<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Xcelerate\Models\CompanyField;

class BatchUploadRecord extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public $requiredFields = [];
    public $uniqueFields = [];

    public function batchUpload(){
        return $this->belongsTo(BatchUpload::class, 'batch_id');
    }

    public function importRecord($companyId, $tableName, $record){
        $primaryKey = '';
        switch($tableName){
            case "items":
            $primaryKey = 'crm_item_id';
            break;

            case "users":
            $primaryKey = 'email';
            break;
        }

        $record = json_decode($record, true);
        $recordExist = NULL;

        if(isset($record[$primaryKey])){
            $recordExist = DB::table($tableName)->where('company_id', $companyId)
                            ->where($primaryKey, $record[$primaryKey])
                            ->first();

            if(isset($recordExist)){
                DB::table($tableName)->where('company_id', $companyId)
                    ->where($primaryKey, $record[$primaryKey])
                    ->update([
                        'is_deleted' => "1",
                        'is_sync' => false
                    ]);
            }
        }
       
        $mappedCompanyFields = $this->mappedCompanyFields($companyId, $tableName);
        $requiredFields = $this->requiredFields;
        $uniqueFields = $this->uniqueFields;

        $canAddOrUpdate = true;
        foreach($mappedCompanyFields as $key => $value){
            
            if(in_array($key, $requiredFields) && (!isset($record[$key]))){
                $canAddOrUpdate = false;
                $this->update([
                    'status' => 'failed',
                    'message' => $key.' is a required field.',
                ]);

                break;
            }
            else if(in_array($key, $uniqueFields)){
                if(!isset($record[$key])){
                    $canAddOrUpdate = false;
                }
                else{
                    $rowExist = DB::table($tableName)
                    ->where('company_id', $companyId)
                    ->where($key, $record[$key])
                    ->get();

                    if(count($rowExist->toArray()) > 1){
                        $canAddOrUpdate = false;
                        $this->update([
                            'status' => 'failed',
                            'message' => $key.' is a unique field.'
                        ]);
        
                        break;
                    }
                }
            }
        }

        if($canAddOrUpdate && $primaryKey != ''){

            $record['is_deleted'] = "0";
	        $record['company_id'] = $companyId;  
	        $record['is_sync'] = true;
            $record['sync_date_time'] = date("Y-m-d H:i:s");

            if(isset($recordExist)){

                DB::table($tableName)
                    ->where('company_id', $companyId)
                    ->where($primaryKey, $record[$primaryKey])
                    ->update($record);

                $this->update([
                    'status' => 'updated',
                    'message' => 'Record updated successfully.'
                ]);
            }
            else{

                DB::table($tableName)->insert($record);
                $this->update([
                    'status' => 'created',
                    'message' => 'Record inserted successfully.'
                ]);
            }

            return true;
        }else{

            return false;
        }
    }

    public function mappedCompanyFields($companyId, $tableName){
        $mappedCompanyFields = [];
        $companyFields = CompanyField::where('company_id', $companyId)
                                    ->where('table_name', $tableName)
                                    ->where('crm_mapped_field', '<>', '')
                                    ->get();

        foreach($companyFields as $eachCompanyField){
            $mappedCompanyFields[$eachCompanyField->column_name] = $eachCompanyField->crm_mapped_field;

            if($eachCompanyField->is_required === 1){
                $this->requiredFields[] = $eachCompanyField->column_name;
            }
            if($eachCompanyField->is_unique === "yes"){
                $this->uniqueFields[] = $eachCompanyField->column_name;
            }
        }

        return $mappedCompanyFields;
    }
}
