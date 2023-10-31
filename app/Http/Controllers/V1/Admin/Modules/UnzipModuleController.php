<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Modules;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\UnzipUpdateRequest;
use Xcelerate\Space\ModuleInstaller;

class UnzipModuleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Xcelerate\Http\Requests\UnzipUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UnzipUpdateRequest $request)
    {
        $this->authorize('manage modules');

        $path = ModuleInstaller::unzip($request->module, $request->path);

        return response()->json([
            'success' => true,
            'path' => $path
        ]);
    }
}
