<?php

namespace Xcelerate\Http\Controllers\V1\Webhook;

use Xcelerate\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronJobController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Artisan::call('schedule:run');

        return response()->json(['success' => true]);
    }
}
