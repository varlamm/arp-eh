<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Role;

use Xcelerate\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Role;
use Illuminate\Support\Facades\DB;

class AbilitiesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $roleId = DB::table('assigned_roles')
                    ->where('entity_id', $user->id)
                    ->where('scope', $request->header('company'))
                    ->value('role_id');

        $roleName = DB::table('roles')
                        ->where('id', $roleId)
                        ->value('name');

        $response =  response()->json(['abilities' => config('abilities.standard')]);

        if($roleName === 'super admin'){
            $response =  response()->json(['abilities' => config('abilities.abilities')]);
        }
        else if($roleName === 'admin'){
            $response =  response()->json(['abilities' => config('abilities.admin')]);
        }

        return $response;
    }
}
