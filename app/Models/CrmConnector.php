<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Xcelerate\Models\CompanySetting;

class CrmConnector extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function connectCrm($companyId, $params, $mode='production')
    {
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->connectCrm($params, $mode);
        }        
    }

    public function oAuthCallback(Request $request){
        $crmConfig = session('zoho_config');
        if(isset($crmConfig)){
            $companyId = $crmConfig['company_id'];
            if(isset($companyId)){
                $crmObj = $this->getAdapter($companyId);
                return $crmObj->oAuthCallback($request);
            }
        }
    }

    public function generateRefreshToken($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->generateRefreshToken($companyId);
        }
    }

    public function syncProducts($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->syncProducts();
        }
    }

    public function fetchCrmProducts($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->fetchCrmProducts();
        }
    }

    public function fetchCrmUsers($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->fetchCrmUsers();
        }
    }

    public function syncUsers($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->syncUsers();
        }
    }

    public function syncRoles($companyId){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->syncRoles();
        }
    }

    public function companyFieldMapping($companyId, $formData, $tableName){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->companyFieldMapping($formData, $tableName);
        }
    }

    public function fetchTableColumns($companyId, $tableName){
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->fetchTableColumns($tableName);
        }
    }

    public function getAdapter($companyId){
        $companySettings = CompanySetting::where('company_id', $companyId)
                                    ->where('option', 'company_crm')
                                    ->first();
        if(isset($companySettings)){
            if($companySettings->value !== 'none'){
                $crmAdapter = 'Xcelerate\Models\Crm\Providers\\' . ucfirst($companySettings->value) . '\\' . ucfirst($companySettings->value) . 'Adapter';
                $crmAdapterObj = new $crmAdapter($companyId);
                return $crmAdapterObj;
            }
        }     
    }
}
