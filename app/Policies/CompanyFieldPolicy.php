<?php

namespace Xcelerate\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Xcelerate\Models\User;
use Xcelerate\Models\CompanyField;
use Silber\Bouncer\BouncerFacade;

class CompanyFieldPolicy
{
    use HandlesAuthorization;

     /**
     * Determine whether the user can view any models.
     *
     * @param  \Xcelerate\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if (BouncerFacade::can('view-company-field', CompanyField::class)) {
            return true;
        }

        return false;
    }
}
