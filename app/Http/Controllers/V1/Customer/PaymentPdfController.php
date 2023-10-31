<?php

namespace Xcelerate\Http\Controllers\V1\Customer;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\PaymentResource;
use Xcelerate\Models\EmailLog;
use Xcelerate\Models\Payment;
use Illuminate\Http\Request;

class PaymentPdfController extends Controller
{
    public function getPdf(EmailLog $emailLog, Request $request)
    {
        if (! $emailLog->isExpired()) {
            return $emailLog->mailable->getGeneratedPDFOrStream('payment');
        }

        abort(403, 'Link Expired.');
    }

    public function getPayment(EmailLog $emailLog)
    {
        $payment = Payment::find($emailLog->mailable_id);

        return new PaymentResource($payment);
    }
}
