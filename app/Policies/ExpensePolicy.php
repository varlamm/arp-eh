<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\Expense;
use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class ExpensePolicy
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
        if (BouncerFacade::can('view-expense', Expense::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Expense  $expense
     * @return mixed
     */
    public function view(User $user, Expense $expense)
    {
        if (BouncerFacade::can('view-expense', $expense) && $user->hasCompany($expense->company_id)) {
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
        if (BouncerFacade::can('create-expense', Expense::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Expense  $expense
     * @return mixed
     */
    public function update(User $user, Expense $expense)
    {
        if (BouncerFacade::can('edit-expense', $expense) && $user->hasCompany($expense->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Expense  $expense
     * @return mixed
     */
    public function delete(User $user, Expense $expense)
    {
        if (BouncerFacade::can('delete-expense', $expense) && $user->hasCompany($expense->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Expense  $expense
     * @return mixed
     */
    public function restore(User $user, Expense $expense)
    {
        if (BouncerFacade::can('delete-expense', $expense) && $user->hasCompany($expense->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\Expense  $expense
     * @return mixed
     */
    public function forceDelete(User $user, Expense $expense)
    {
        if (BouncerFacade::can('delete-expense', $expense) && $user->hasCompany($expense->company_id)) {
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
        if (BouncerFacade::can('delete-expense', Expense::class)) {
            return true;
        }

        return false;
    }
}
