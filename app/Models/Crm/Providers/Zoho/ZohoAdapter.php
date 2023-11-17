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

    public function __construct()
    {
    }

    public function initialize()
    {
        if(!isset(self::$instance)){
            self::$instance = new ZohoCrm();
        }

        return self::$instance;
    }

    public function connectCrm($params, $company_id, $mode='production')
    {
        $crmObj = $this->initialize();
        return $crmObj->connect($params, $company_id, $mode);
    }

    public function oAuthCallback(Request $request){
        $crmObj = $this->initialize();
        return $crmObj->oAuthCallback($request);
    }
}
