<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\Item;
use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class ItemPolicy
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
        if (BouncerFacade::can('view-item', Item::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Item  $item
     * @return mixed
     */
    public function view(User $user, Item $item)
    {
        if (BouncerFacade::can('view-item', $item) && $user->hasCompany($item->company_id)) {
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
        if (BouncerFacade::can('create-item', Item::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Item  $item
     * @return mixed
     */
    public function update(User $user, Item $item)
    {
        if (BouncerFacade::can('edit-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Item  $item
     * @return mixed
     */
    public function delete(User $user, Item $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Item  $item
     * @return mixed
     */
    public function restore(User $user, Item $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Item  $item
     * @return mixed
     */
    public function forceDelete(User $user, Item $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
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
        if (BouncerFacade::can('delete-item', Item::class)) {
            return true;
        }

        return false;
    }
}
