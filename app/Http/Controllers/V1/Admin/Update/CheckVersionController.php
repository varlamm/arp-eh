<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Update;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Setting;
use Xcelerate\Space\Updater;
use Illuminate\Http\Request;

class CheckVersionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        if ((! $request->user()) || (! $request->user()->isOwner())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to update this app.'
            ], 401);
        }

        set_time_limit(600); // 10 minutes

        $json = Updater::checkForUpdate(Setting::getSetting('version'));

        return response()->json($json);
    }
}
