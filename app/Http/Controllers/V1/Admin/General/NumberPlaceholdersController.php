<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Services\SerialNumberFormatter;
use Illuminate\Http\Request;

class NumberPlaceholdersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->format) {
            $placeholders = SerialNumberFormatter::getPlaceholders($request->format);
        } else {
            $placeholders = [];
        }

        return response()->json([
            'success' => true,
            'placeholders' => $placeholders,
        ]);
    }
}
