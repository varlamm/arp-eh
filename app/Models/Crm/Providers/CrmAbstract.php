<?php
namespace Xcelerate\Models\Crm\Providers;

abstract class CrmAbstract
{
    abstract function initialize();

    public function getUrl($url, $parmas, $method="GET", $headers=null){
    
    }

}