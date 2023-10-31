<?php

namespace Xcelerate\Http\Controllers\V1\Customer\Estimate;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\Customer\EstimateResource;
use Xcelerate\Models\Company;
use Xcelerate\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptEstimateController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  Estimate $estimate
    * @return \Illuminate\Http\Response
    */
    public function __invoke(Request $request, Company $company, $id)
    {
        $estimate = $company->estimates()
            ->whereCustomer(Auth::guard('customer')->id())
            ->where('id', $id)
            ->first();

        if (! $estimate) {
            return response()->json(['error' => 'estimate_not_found'], 404);
        }

        $estimate->update($request->only('status'));

        return new EstimateResource($estimate);
    }
}
