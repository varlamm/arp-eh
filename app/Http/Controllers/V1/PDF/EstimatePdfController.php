<?php

namespace Xcelerate\Http\Controllers\V1\PDF;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Estimate;
use Illuminate\Http\Request;

class EstimatePdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Estimate $estimate)
    {
        if ($request->has('preview')) {
            return $estimate->getPDFData();
        }


        return $estimate->getGeneratedPDFOrStream('estimate');
    }
}
