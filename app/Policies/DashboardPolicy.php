<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\Company;
use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class DashboardPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Company $company)
    {
        if (BouncerFacade::can('dashboard') && $user->hasCompany($company->id)) {
            return true;
        }

        return false;
    }
}
