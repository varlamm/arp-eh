<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Xcelerate\Models\CompanySetting;
// use Xcelerate\Models\Crm\Providers\Zoho\ZohoAdapter;
// use Xcelerate\Models\Crm\Providers\Zoho\ZohoCrm;

class CrmConnector extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function connectCrm($companyId, $params, $mode='production')
    {
        if(isset($companyId)){
            $crmObj = $this->getAdapter($companyId);
            return $crmObj->connectCrm($params, $companyId, $mode);
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

    public function getAdapter($companyId){
        $companySettings = CompanySetting::where('company_id', $companyId)
                                    ->where('option', 'company_crm')
                                    ->first();
        if(isset($companySettings)){
            if($companySettings->value !== 'none'){
                $crmAdapter = 'Xcelerate\Models\Crm\Providers\\' . ucfirst($companySettings->value) . '\\' . ucfirst($companySettings->value) . 'Adapter';
                $crmAdapterObj = new $crmAdapter();
                return $crmAdapterObj;
            }
        }     
    }
}
