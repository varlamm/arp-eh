<?php

namespace Xcelerate\Models\Crm\Providers\ZohoCRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Xcelerate\Models\Crm\Providers\ZohoCRM\ZohoCrmClass;

class ZohoCrmAdapter extends CrmAbstract implements CrmAdapterInterface
{

    private $zohoCrmClass;

    public function __construct(ZohoCrmClass $zohoCrmClass)
    {
        $this->zohoCrmClass = $zohoCrmClass;
    }

    public function connectCrm($client, $secret, $return_url=false): bool
    {
        return $this->zohoCrmClass->connect($client , $secret, $return_url);
    }

  
}
