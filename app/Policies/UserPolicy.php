<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        if ($user->isOwner()) {
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
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
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
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can invite the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\User  $model
     * @return mixed
     */
    public function invite(User $user, User $model)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param  \Xcelerate\Models\User  $user
     * @return mixed
     */
    public function deleteMultiple(User $user)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }
}
