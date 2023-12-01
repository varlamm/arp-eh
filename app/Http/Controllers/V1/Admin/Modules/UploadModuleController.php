<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Modules;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\UploadModuleRequest;
use Xcelerate\Space\ModuleInstaller;

class UploadModuleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Xcelerate\Http\Requests\UploadModuleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UploadModuleRequest $request)
    {
        $this->authorize('manage modules');

        $response = ModuleInstaller::upload($request);

        return response()->json($response);
    }
}
