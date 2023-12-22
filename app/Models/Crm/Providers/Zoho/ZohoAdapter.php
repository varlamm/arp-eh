<?php
namespace Xcelerate\Models\Crm\Providers\Zoho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Xcelerate\Models\Crm\Providers\CrmAbstract;
use Xcelerate\Models\Crm\Providers\CrmAdapterInterface;
use Xcelerate\Models\Crm\Providers\Zoho\ZohoCrm;

class ZohoAdapter extends CrmAbstract implements CrmAdapterInterface
{
    private static $instance;
    public static $company_id;

    public function __construct($companyId)
    {
        self::$company_id = $companyId;
    }

    public function initialize()
    {
        if(!isset(self::$instance)){
            self::$instance = new ZohoCrm(self::$company_id);
        }

        return self::$instance;
    }

    public function connectCrm($params, $mode='production')
    {
        $crmObj = $this->initialize();
        return $crmObj->connect($params, $mode);
    }

    public function oAuthCallback(Request $request){
        $crmObj = $this->initialize();
        return $crmObj->oAuthCallback($request);
    }

    public function generateRefreshToken(){
        $crmObj = $this->initialize();
        return $crmObj->generateRefreshToken();
    }

    public function syncProducts(){
        $crmObj = $this->initialize();
        return $crmObj->syncProducts();
    }
    
    public function fetchCrmProducts(){
        $crmObj = $this->initialize();
        return $crmObj->fetchCrmProducts();
    }

    public function companyFieldMapping($formData, $tableName){
        $crmObj = $this->initialize();
        return $crmObj->companyFieldMapping($formData, $tableName);
    }

    public function fetchTableColumns($tableName){
        $crmObj = $this->initialize();
        return $crmObj->fetchTableColumns($tableName);
    }       
}
