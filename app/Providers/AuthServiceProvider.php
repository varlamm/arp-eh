<?php

namespace Xcelerate\Providers;

use Xcelerate\Policies\CompanyPolicy;
use Xcelerate\Policies\CustomerPolicy;
use Xcelerate\Policies\DashboardPolicy;
use Xcelerate\Policies\EstimatePolicy;
use Xcelerate\Policies\ExpensePolicy;
use Xcelerate\Policies\InvoicePolicy;
use Xcelerate\Policies\ItemPolicy;
use Xcelerate\Policies\ModulesPolicy;
use Xcelerate\Policies\NotePolicy;
use Xcelerate\Policies\OwnerPolicy;
use Xcelerate\Policies\PaymentPolicy;
use Xcelerate\Policies\RecurringInvoicePolicy;
use Xcelerate\Policies\ReportPolicy;
use Xcelerate\Policies\SettingsPolicy;
use Xcelerate\Policies\UserPolicy;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Xcelerate\Models\Customer::class => \Xcelerate\Policies\CustomerPolicy::class,
        \Xcelerate\Models\Invoice::class => \Xcelerate\Policies\InvoicePolicy::class,
        \Xcelerate\Models\Estimate::class => \Xcelerate\Policies\EstimatePolicy::class,
        \Xcelerate\Models\Payment::class => \Xcelerate\Policies\PaymentPolicy::class,
        \Xcelerate\Models\Expense::class => \Xcelerate\Policies\ExpensePolicy::class,
        \Xcelerate\Models\ExpenseCategory::class => \Xcelerate\Policies\ExpenseCategoryPolicy::class,
        \Xcelerate\Models\PaymentMethod::class => \Xcelerate\Policies\PaymentMethodPolicy::class,
        \Xcelerate\Models\TaxType::class => \Xcelerate\Policies\TaxTypePolicy::class,
        \Xcelerate\Models\CustomField::class => \Xcelerate\Policies\CustomFieldPolicy::class,
        \Xcelerate\Models\CompanyField::class => \Xcelerate\Policies\CustomFieldPolicy::class,
        \Xcelerate\Models\User::class => \Xcelerate\Policies\UserPolicy::class,
        \Xcelerate\Models\Item::class => \Xcelerate\Policies\ItemPolicy::class,
        \Silber\Bouncer\Database\Role::class => \Xcelerate\Policies\RolePolicy::class,
        \Xcelerate\Models\Unit::class => \Xcelerate\Policies\UnitPolicy::class,
        \Xcelerate\Models\RecurringInvoice::class => \Xcelerate\Policies\RecurringInvoicePolicy::class,
        \Xcelerate\Models\ExchangeRateProvider::class => \Xcelerate\Policies\ExchangeRateProviderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create company', [CompanyPolicy::class, 'create']);
        Gate::define('transfer company ownership', [CompanyPolicy::class, 'transferOwnership']);
        Gate::define('delete company', [CompanyPolicy::class, 'delete']);

        Gate::define('manage modules', [ModulesPolicy::class, 'manageModules']);

        Gate::define('manage settings', [SettingsPolicy::class, 'manageSettings']);
        Gate::define('manage company', [SettingsPolicy::class, 'manageCompany']);
        Gate::define('manage backups', [SettingsPolicy::class, 'manageBackups']);
        Gate::define('manage file disk', [SettingsPolicy::class, 'manageFileDisk']);
        Gate::define('manage email config', [SettingsPolicy::class, 'manageEmailConfig']);
        Gate::define('manage notes', [NotePolicy::class, 'manageNotes']);
        Gate::define('view notes', [NotePolicy::class, 'viewNotes']);

        Gate::define('send invoice', [InvoicePolicy::class, 'send']);
        Gate::define('send estimate', [EstimatePolicy::class, 'send']);
        Gate::define('send payment', [PaymentPolicy::class, 'send']);

        Gate::define('delete multiple items', [ItemPolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple customers', [CustomerPolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple users', [UserPolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple invoices', [InvoicePolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple estimates', [EstimatePolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple expenses', [ExpensePolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple payments', [PaymentPolicy::class, 'deleteMultiple']);
        Gate::define('delete multiple recurring invoices', [RecurringInvoicePolicy::class, 'deleteMultiple']);

        Gate::define('view dashboard', [DashboardPolicy::class, 'view']);

        Gate::define('view report', [ReportPolicy::class, 'viewReport']);

        Gate::define('owner only', [OwnerPolicy::class, 'managedByOwner']);
    }
}
