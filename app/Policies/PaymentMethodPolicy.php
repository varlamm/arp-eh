<?php

namespace Xcelerate\Policies;

use Xcelerate\Models\Payment;
use Xcelerate\Models\PaymentMethod;
use Xcelerate\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class PaymentMethodPolicy
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
        if (BouncerFacade::can('view-payment', Payment::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\PaymentMethod  $paymentMethod
     * @return mixed
     */
    public function view(User $user, PaymentMethod $paymentMethod)
    {
        if (BouncerFacade::can('view-payment', Payment::class) && $user->hasCompany($paymentMethod->company_id)) {
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
        if (BouncerFacade::can('view-payment', Payment::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\PaymentMethod  $paymentMethod
     * @return mixed
     */
    public function update(User $user, PaymentMethod $paymentMethod)
    {
        if (BouncerFacade::can('view-payment', Payment::class) && $user->hasCompany($paymentMethod->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\PaymentMethod  $paymentMethod
     * @return mixed
     */
    public function delete(User $user, PaymentMethod $paymentMethod)
    {
        if (BouncerFacade::can('view-payment', Payment::class) && $user->hasCompany($paymentMethod->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\PaymentMethod  $paymentMethod
     * @return mixed
     */
    public function restore(User $user, PaymentMethod $paymentMethod)
    {
        if (BouncerFacade::can('view-payment', Payment::class) && $user->hasCompany($paymentMethod->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Xcelerate\Models\User  $user
     * @param  \Xcelerate\Models\PaymentMethod  $paymentMethod
     * @return mixed
     */
    public function forceDelete(User $user, PaymentMethod $paymentMethod)
    {
        if (BouncerFacade::can('view-payment', Payment::class) && $user->hasCompany($paymentMethod->company_id)) {
            return true;
        }

        return false;
    }
}
