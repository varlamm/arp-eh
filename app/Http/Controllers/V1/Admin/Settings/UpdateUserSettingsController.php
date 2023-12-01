<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\UpdateSettingsRequest;

class UpdateUserSettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\UpdateSettingsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateSettingsRequest $request)
    {
        $user = $request->user();

        $user->setSettings($request->settings);

        return response()->json([
            'success' => true,
        ]);
    }
}
