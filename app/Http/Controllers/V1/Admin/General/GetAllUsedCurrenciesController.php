<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Currency;
use Xcelerate\Models\Estimate;
use Xcelerate\Models\Invoice;
use Xcelerate\Models\Payment;
use Xcelerate\Models\Tax;
use Illuminate\Http\Request;

class GetAllUsedCurrenciesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $invoices = Invoice::where('exchange_rate', null)->pluck('currency_id')->toArray();

        $taxes = Tax::where('exchange_rate', null)->pluck('currency_id')->toArray();

        $estimates = Estimate::where('exchange_rate', null)->pluck('currency_id')->toArray();

        $payments = Payment::where('exchange_rate', null)->pluck('currency_id')->toArray();

        $currencies = array_merge($invoices, $taxes, $estimates, $payments);

        return response()->json([
            'currencies' => Currency::whereIn('id', $currencies)->get()
        ]);
    }
}
