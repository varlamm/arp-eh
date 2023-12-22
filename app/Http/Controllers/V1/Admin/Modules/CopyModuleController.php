<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Modules;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Space\ModuleInstaller;
use Illuminate\Http\Request;

class CopyModuleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage modules');

        $response = ModuleInstaller::copyFiles($request->module, $request->path);

        return response()->json([
            'success' => $response
        ]);
    }
}
