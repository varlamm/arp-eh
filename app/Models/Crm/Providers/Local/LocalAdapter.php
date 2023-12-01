<?php
namespace Xcelerate\Models\Crm\Providers\Local;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Xcelerate\Models\Crm\Providers\Local\LocalCrm;
use Xcelerate\Models\Crm\Providers\CrmAbstract;
use Xcelerate\Models\Crm\Providers\CrmAdapterInterface;

class LocalAdapter extends CrmAbstract implements CrmAdapterInterface
{
    private $localCrm;

    public function __construct(LocalCrm $localCrm)
    {
        $this->localCrm = $localCrm;
    }

    public function initialize()
    {
        
    }

    public function connectCrm($client, $secret, $return_url=false): bool
    {
        return $this->localCrm->connect($client , $secret, $return_url);
    }

}
