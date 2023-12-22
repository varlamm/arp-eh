<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Company;
use Illuminate\Http\Request;

class CompanyCurrencyCheckTransactionsController extends Controller
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

        $this->authorize('manage company', $company);

        return response()->json([
            'has_transactions' => $company->hasTransactions(),
        ]);
    }
}
