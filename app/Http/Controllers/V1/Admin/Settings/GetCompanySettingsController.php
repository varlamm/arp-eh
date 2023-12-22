<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\GetSettingsRequest;
use Xcelerate\Models\CompanySetting;

class GetCompanySettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(GetSettingsRequest $request)
    {
        $settings = CompanySetting::getSettings($request->settings, $request->header('company'));

        return response()->json($settings);
    }
}
