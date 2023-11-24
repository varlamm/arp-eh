<?php
namespace Xcelerate\Models\Crm\Providers;

interface CrmAdapterInterface
{

    // public function connectCrm($client, $secret, $return_url=false);
    public function connectCrm($connectionParams, $mode='production');

}