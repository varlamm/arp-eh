<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Config;

use Xcelerate\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FiscalYearsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'fiscal_years' => config('xcelerate.fiscal_years'),
        ]);
    }
}
