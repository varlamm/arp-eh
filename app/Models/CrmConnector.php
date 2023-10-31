<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmConnector extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function connectCrm()
    {
       
        
        $payPalGateway = new PayPalGateway();
        $payPalAdapter = new PayPalAdapter($payPalGateway);
        $payPalAdapter->processPayment(100.00);

        return $name;
    }

  
}
