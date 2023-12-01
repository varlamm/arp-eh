<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\CountryResource;
use Xcelerate\Models\Country;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $countries = Country::all();

        return CountryResource::collection($countries);
    }
}
