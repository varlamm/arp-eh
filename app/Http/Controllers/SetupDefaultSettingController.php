<?php

namespace Xcelerate\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Silber\Bouncer\BouncerFacade;
use Xcelerate\Models\Company;
use Xcelerate\Models\User;

class SetupDefaultSettingController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function superAdminPermissions(Request $request){

        BouncerFacade::scope()->to(1);

        $superAdmin = BouncerFacade::role()->firstOrCreate([
            'name' => 'super admin',
            'title' => 'Super Admin',
            'scope' => 1
        ]);

        foreach (config('abilities.abilities') as $ability) {
            BouncerFacade::allow($superAdmin)->to($ability['ability'], $ability['model']);
        }

        $user = User::where('id', 1)->first();
        $company = Company::where('id', 1)->first();
        $user->companies()->attach($company->id);
        $user->assign('super admin');

        $return = true;

        return $return;
    }
}
