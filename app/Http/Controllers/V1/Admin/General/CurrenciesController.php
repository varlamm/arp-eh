<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\CurrencyResource;
use Xcelerate\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $currencies = Currency::latest()->get();

        return CurrencyResource::collection($currencies);
    }

    public function getCurrencyData(Request $request){
        $currencyCode = $request->currency_code;
        $currencyRow = [];
        if(isset($currencyCode)){
            $zohoCurrencyCode = $currencyCode;
            if($currencyCode == 'ROW' || $currencyCode == 'SAARC'){
                $currencyCode = 'USD';
            }
            $currency = Currency::where('code', $currencyCode)->first();
            $currencyRow['id'] = $currency->id;
            $currencyRow['name'] = $currency->name;
            $currencyRow['code'] = $currency->code;
            $currencyRow['zoho_code'] = $zohoCurrencyCode;
            $currencyRow['decimal_separator'] = $currency->decimal_separator;
            $currencyRow['precision'] = $currency->precision;
            $currencyRow['swap_currency_symbol'] = $currency->swap_currency_symbol;
            $currencyRow['symbol'] = $currency->symbol;
            $currencyRow['thousand_separator'] = $currency->thousand_separator;
            $currencyRow['updated_at'] = $currency->updated_at;
            $currencyRow['created_at'] = $currency->created_at;
        }
        return $currencyRow;
    }

    public function allCurrencies(Request $request){

        $currencies = Currency::where('id', '>', 0)->get()->toArray();

        return response()->json($currencies, 200);
    }
}
