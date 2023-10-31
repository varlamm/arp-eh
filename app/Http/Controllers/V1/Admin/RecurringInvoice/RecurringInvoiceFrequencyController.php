<?php

namespace Xcelerate\Http\Controllers\V1\Admin\RecurringInvoice;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\RecurringInvoice;
use Illuminate\Http\Request;

class RecurringInvoiceFrequencyController extends Controller
{
    public function __invoke(Request $request)
    {
        $nextInvoiceAt = RecurringInvoice::getNextInvoiceDate($request->frequency, $request->starts_at);

        return response()->json([
            'success' => true,
            'next_invoice_at' => $nextInvoiceAt,
        ]);
    }
}
