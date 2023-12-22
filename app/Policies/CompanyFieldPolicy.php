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

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\CompanyField  $companyField
     * @return mixed
     */
    public function view(User $user, CompanyField $companyField)
    {
        if (BouncerFacade::can('view-company-field', $companyField) && $user->hasCompany($companyField->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Xcelerate\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (BouncerFacade::can('create-company-field', CompanyField::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\CompanyField  $companyField
     * @return mixed
     */
    public function update(User $user, CompanyField $companyField)
    {
        if (BouncerFacade::can('edit-company-field', $companyField) && $user->hasCompany($companyField->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\CompoanyField  $companyField
     * @return mixed
     */
    public function delete(User $user, CompanyField $companyField)
    {
        if (BouncerFacade::can('delete-company-field', $companyField) && $user->hasCompany($companyField->company_id)) {
            return true;
        }

        return false;
    }
}
