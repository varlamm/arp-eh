<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\Company;
use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewReport(User $user, Company $company)
    {
        if (BouncerFacade::can('view-financial-reports') && $user->hasCompany($company->id)) {
            return true;
        }

        return false;
    }
}
