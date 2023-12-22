<?php

namespace Xcelerate\Http\Controllers\V1\PDF;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Invoice;
use Illuminate\Http\Request;

class InvoicePdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Invoice $invoice)
    {
        if ($request->has('preview')) {
            return $invoice->getPDFData();
        }

        return $invoice->getGeneratedPDFOrStream('invoice');
    }
}
