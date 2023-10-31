<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Estimate;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Estimate;
use Illuminate\Http\Request;

class ChangeEstimateStatusController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  Estimate $estimate
    * @return \Illuminate\Http\Response
    */
    public function __invoke(Request $request, Estimate $estimate)
    {
        $this->authorize('send estimate', $estimate);

        $estimate->update($request->only('status'));

        return response()->json([
            'success' => true,
        ]);
    }
}
