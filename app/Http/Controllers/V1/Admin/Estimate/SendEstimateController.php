<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Estimate;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\SendEstimatesRequest;
use Xcelerate\Models\Estimate;

class SendEstimateController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Xcelerate\Http\Requests\SendEstimatesRequest  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(SendEstimatesRequest $request, Estimate $estimate)
    {
        $this->authorize('send estimate', $estimate);

        $response = $estimate->send($request->all());

        return response()->json($response);
    }
}
