<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Company;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\CompanyResource;
use Xcelerate\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $company = Company::find($request->header('company'));

        return new CompanyResource($company);
    }
}
