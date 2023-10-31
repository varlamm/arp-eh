<?php

namespace Xcelerate\Models\Crm\Providers\ZohoCRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Xcelerate\Models\Crm\Providers\ZohoCRM\ZohoCrmClass;

class ZohoCrmClass
{


    public function __construct()
    {
       
    }

    public function connect($client, $secret, $return_url=false): bool
    {
        return $this->connect->connect($client , $secret, $return_url);
    }

  
}
