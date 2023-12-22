<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\SettingRequest;
use Xcelerate\Models\Setting;
use Illuminate\Http\Request;

class UpdateSettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SettingRequest $request)
    {
        $this->authorize('manage settings');

        Setting::setSettings($request->settings);

        return response()->json([
            'success' => true,
            $request->settings
        ]);
    }
}
