<?php

// Implementation taken from nova-backup-tool - https://github.com/spatie/nova-backup-tool/

namespace Xcelerate\Http\Controllers\V1\Admin\Backup;

use Xcelerate\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     *
     * @return JsonResponse
     */
    public function respondSuccess(): JsonResponse
    {
        return response()->json([
            'success' => true,
        ]);
    }
}
