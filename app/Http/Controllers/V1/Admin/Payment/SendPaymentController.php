<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Payment;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\SendPaymentRequest;
use Xcelerate\Models\Payment;

class SendPaymentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SendPaymentRequest $request, Payment $payment)
    {
        $this->authorize('send payment', $payment);

        $response = $payment->send($request->all());

        return response()->json($response);
    }
}
