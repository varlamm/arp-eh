<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Estimate;
use Xcelerate\Models\Invoice;
use Xcelerate\Models\Payment;
use Xcelerate\Services\SerialNumberFormatter;
use Illuminate\Http\Request;

class NextNumberController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Invoice $invoice, Estimate $estimate, Payment $payment)
    {
        $key = $request->key;
        $nextNumber = null;
        $serial = (new SerialNumberFormatter())
            ->setCompany($request->header('company'))
            ->setCustomer($request->userId);

        try {
            switch ($key) {
                case 'invoice':
                    $nextNumber = $serial->setModel($invoice)
                        ->setModelObject($request->model_id)
                        ->getNextNumber();

                    break;

                case 'estimate':
                    $nextNumber = $serial->setModel($estimate)
                        ->setModelObject($request->model_id)
                        ->getNextNumber();

                    break;

                case 'payment':
                    $nextNumber = $serial->setModel($payment)
                        ->setModelObject($request->model_id)
                        ->getNextNumber();

                    break;

                default:
                    return;
            }
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'nextNumber' => $nextNumber,
        ]);
    }
}
