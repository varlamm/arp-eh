<?php

namespace Crater\Http\Controllers\V1\Installation;

use Auth;
use Crater\Http\Controllers\Controller;
use Crater\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as Authenticatable;  

class LoginController extends Authenticatable
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = User::where('role', 'super admin')->firstOrFail();
        Auth::login($user);

        return response()->json([
            'success' => true,
            'user' => $user,
            'company' => $user->companies()->firstOrFail()
        ]);
    }
}
