<?php
namespace Xcelerate\Models\Crm\Providers\Local;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalCrm
{
    public function __construct()
    {
       
    }

    public function connect($client, $secret, $return_url=false): bool
    {
        return $this->connect($client , $secret, $return_url);
    }

  
}
