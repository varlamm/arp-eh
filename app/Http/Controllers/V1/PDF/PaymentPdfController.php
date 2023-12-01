<?php

namespace Xcelerate\Http\Controllers\V1\PDF;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Payment;
use Illuminate\Http\Request;

class PaymentPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Payment $payment)
    {
        if ($request->has('preview')) {
            return view('app.pdf.payment.payment');
        }

        return $payment->getGeneratedPDFOrStream('payment');
    }
}
