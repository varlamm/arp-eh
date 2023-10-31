<?php

namespace Xcelerate\Http\Controllers\V1\Customer\Payment;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\Customer\PaymentMethodResource;
use Xcelerate\Models\Company;
use Xcelerate\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Company $company)
    {
        return PaymentMethodResource::collection(PaymentMethod::where('company_id', $company->id)->get());
    }
}
