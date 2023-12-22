<?php

namespace Xcelerate\Http\Controllers\V1\Admin\ExchangeRate;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\ExchangeRateProvider;
use Xcelerate\Traits\ExchangeRateProvidersTrait;
use Illuminate\Http\Request;

class GetSupportedCurrenciesController extends Controller
{
    use ExchangeRateProvidersTrait;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', ExchangeRateProvider::class);

        return $this->getSupportedCurrencies($request);
    }
}
