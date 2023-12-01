<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Modules;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Space\ModuleInstaller;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
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

        $response = ModuleInstaller::checkToken($request->api_token);

        return $response;
    }
}
