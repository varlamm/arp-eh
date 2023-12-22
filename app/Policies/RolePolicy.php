<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\Database\Role;
use Silber\Bouncer\BouncerFacade;

class RolePolicy
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
        if (BouncerFacade::can('view-role', Role::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Silber\Bouncer\Database\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        if (BouncerFacade::can('view-role', $role)) {
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
        if (BouncerFacade::can('create-role', Role::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Silber\Bouncer\Database\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        if (BouncerFacade::can('edit-role', $role)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Silber\Bouncer\Database\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        if (BouncerFacade::can('delete-role', $role)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Silber\Bouncer\Database\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Silber\Bouncer\Database\Role  $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        if (BouncerFacade::can('delete-role', $role)) {
            return true;
        }

        return false;
    }
}
