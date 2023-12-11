<?php

namespace Database\Seeders;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;
use Xcelerate\Models\Company;
use Xcelerate\Models\CompanyField;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createItemsColumns();
        $this->createUsersColumns();
        $this->createCrmRolesColumns();
    }

    public function createItemsColumns(){
        $itemName = CompanyField::where('company_id', 0)
                        ->where('table_name', 'items')
                        ->where('column_name', 'name')
                        ->first();

        if(!isset($itemName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'name',
                'label' => 'Name',
                'table_name' => 'items',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemDescription = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'description')
                            ->first();

        if(!isset($itemDescription)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'description',
                'label' => 'Description',
                'table_name' => 'items',
                'column_type' => 'TextArea',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemPrice = CompanyField::where('company_id', 0)
                        ->where('table_name', 'items')
                        ->where('column_name', 'price')
                        ->first();

        if(!isset($itemPrice)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'price',
                'label' => 'Price',
                'table_name' => 'items',
                'column_type' => 'Price',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }


        $itemCompanyId = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'company_id')
                            ->first();

        if(!isset($itemCompanyId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'company_id',
                'label' => 'Company ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemUnitId = CompanyField::where('company_id', 0)
                        ->where('table_name', 'items')
                        ->where('column_name', 'unit_id')
                        ->first();

        if(!isset($itemUnitId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'unit_id',
                'label' => 'Unit ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemCurrencyId = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'currency_id')
                            ->first();

        if(!isset($itemCurrencyId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'currency_id',
                'label' => 'Currency ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }                   

        $itemCurrencySymbol = CompanyField::where('company_id', 0)
                                ->where('table_name', 'items')
                                ->where('column_name', 'currency_symbol')
                                ->first();

        if(!isset($itemCurrencySymbol)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'currency_symbol',
                'label' => 'Currency Symbol',
                'table_name' => 'items',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemTaxPerItem = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'tax_per_item')
                            ->first();

        if(!isset($itemTaxPerItem)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'tax_per_item',
                'label' => 'Tax Per Item ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemCreatorId = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'creator_id')
                            ->first();

        if(!isset($itemCreatorId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'creator_id',
                'label' => 'Creator ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }
        
        $itemIsSync = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'is_sync')
                            ->first();

        if(!isset($itemIsSync)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'is_sync',
                'label' => 'Item Is Sync',
                'table_name' => 'items',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemIsDeleted = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'is_deleted')
                            ->first();

        if(!isset($itemIsDeleted)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'is_deleted',
                'label' => 'Item Is Deleted',
                'table_name' => 'items',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemCrmId = CompanyField::where('company_id', 0)
                            ->where('table_name', 'items')
                            ->where('column_name', 'crm_item_id')
                            ->first();

        if(!isset($itemCrmId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_item_id',
                'label' => 'Item Crm ID',
                'table_name' => 'items',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }
        
        $itemSyncDateTime = CompanyField::where('company_id', 0)
                        ->where('table_name', 'items')
                        ->where('column_name', 'sync_date_time')
                        ->first();

        if(!isset($itemSyncDateTime)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'sync_date_time',
                'label' => 'Item Sync DateTime',
                'table_name' => 'items',
                'column_type' => 'DateTime',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $itemCode = CompanyField::where('company_id', 0)
                        ->where('table_name', 'items')
                        ->where('column_name', 'item_code')
                        ->first();

        if(!isset($itemCode)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'item_code',
                'label' => 'Item Code',
                'table_name' => 'items',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }
        return true;
    }

    public function createUsersColumns(){
        $usersName = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'name')
                            ->first();

        if(!isset($usersName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'name',
                'label' => 'Name',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersEmail = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'email')
                            ->first();

        if(!isset($usersEmail)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'email',
                'label' => 'Email',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersPhone = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'phone')
                            ->first();

        if(!isset($usersPhone)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'phone',
                'label' => 'Phone',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersPassword = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'password')
                            ->first();

        if(!isset($usersPassword)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'password',
                'label' => 'Password',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersRole = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'role')
                            ->first();

        if(!isset($usersRole)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'role',
                'label' => 'Role',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersRememberToken = CompanyField::where('company_id', 0)
                        ->where('table_name', 'users')
                        ->where('column_name', 'remember_token')
                        ->first();

        if(!isset($usersRememberToken)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'remember_token',
                'label' => 'User Remember Token',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        
        $usersFacebookId = CompanyField::where('company_id', 0)
                        ->where('table_name', 'users')
                        ->where('column_name', 'facebook_id')
                        ->first();

        if(!isset($usersFacebookId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'facebook_id',
                'label' => 'User Facebook ID',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersGoogleId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'google_id')
                                ->first();

        if(!isset($usersGoogleId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'google_id',
                'label' => 'User Google ID',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersGitHubId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'github_id')
                                ->first();

        if(!isset($usersGitHubId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'github_id',
                'label' => 'User GitHub ID',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersContactName = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'contact_name')
                                ->first();

        if(!isset($usersContactName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'contact_name',
                'label' => 'User Contact Name',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCompanyName = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'company_name')
                                ->first();

        if(!isset($usersCompanyName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'company_name',
                'label' => 'User Company Name',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersWebsite = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'website')
                                ->first();

        if(!isset($usersWebsite)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'website',
                'label' => 'User Website',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersEnablePortal = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'enable_portal')
                                ->first();

        if(!isset($usersEnablePortal)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'enable_portal',
                'label' => 'User Enable Portal',
                'table_name' => 'users',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCurrencyId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'currency_id')
                                ->first();

        if(!isset($usersCurrencyId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'currency_id',
                'label' => 'User Currency ID',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCreatorId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'creator_id')
                                ->first();

        if(!isset($usersCreatorId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'creator_id',
                'label' => 'User Creator ID',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersDeletedId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'is_deleted')
                                ->first();

        if(!isset($usersDeletedId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'is_deleted',
                'label' => 'User Is Delted',
                'table_name' => 'users',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCrmSync = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'crm_sync')
                                ->first();

        if(!isset($usersCrmSync)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_sync',
                'label' => 'User Creator ID',
                'table_name' => 'users',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCrmStatusActive = CompanyField::where('company_id', 0)
                                ->where('table_name', 'users')
                                ->where('column_name', 'crm_status_active')
                                ->first();

        if(!isset($usersCrmStatusActive)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_status_active',
                'label' => 'User Crm Status Active',
                'table_name' => 'users',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCrmRoleId = CompanyField::where('company_id', 0)
                                    ->where('table_name', 'users')
                                    ->where('column_name', 'crm_role_id')
                                    ->first();

        if(!isset($usersCrmRoleId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_role_id',
                'label' => 'User Crm Role ID',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCrmId = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'crm_users_id')
                            ->first();

        if(!isset($usersCrmId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_users_id',
                'label' => 'User Crm ID',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersProfileId = CompanyField::where('company_id', 0)
                        ->where('table_name', 'users')
                        ->where('column_name', 'crm_profile_id')
                        ->first();

        if(!isset($usersProfileId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_profile_id',
                'label' => 'User Crm Profile ID',
                'table_name' => 'users',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $usersCrmProfileName = CompanyField::where('company_id', 0)
                            ->where('table_name', 'users')
                            ->where('column_name', 'crm_profile_name')
                            ->first();

        if(!isset($usersCrmProfileName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_profile_name',
                'label' => 'User Crm Profile Name',
                'table_name' => 'users',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }
        return true;
    }

    public function createCrmRolesColumns(){

        $roleId = CompanyField::where('company_id', 0)
                    ->where('table_name', 'crm_roles')
                    ->where('column_name', 'role_id')
                    ->first();

        if(!isset($roleId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'role_id',
                'label' => 'Crm Role ID',
                'table_name' => 'crm_roles',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $roleName = CompanyField::where('company_id', 0)
                        ->where('table_name', 'crm_roles')
                        ->where('column_name', 'role_name')
                        ->first();

        if(!isset($roleName)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'role_name',
                'label' => 'Crm Role Name',
                'table_name' => 'crm_roles',
                'column_type' => 'Input',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $reportingManagerCrm = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'reporting_manager_crm')
                                ->first();

        if(!isset($reportingManagerCrm)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'reporting_manager_crm',
                'label' => 'Crm Reporting Manager ID',
                'table_name' => 'crm_roles',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $reportingManagerId = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'reporting_manager')
                                ->first();

        if(!isset($reportingManagerId)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'reporting_manager',
                'label' => 'Reporting Manager ID',
                'table_name' => 'crm_roles',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $maxDiscountAllowed = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'max_discount_allowed')
                                ->first();

        if(!isset($maxDiscountAllowed)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'max_discount_allowed',
                'label' => 'Crm Max Discount Allowed',
                'table_name' => 'crm_roles',
                'column_type' => 'Price',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $crmRoleIsDeleted = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'is_deleted')
                                ->first();

        if(!isset($crmRoleIsDeleted)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'is_deleted',
                'label' => 'Crm Role Is Deleted',
                'table_name' => 'crm_roles',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $crmRoleIsActive = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'is_active_crm')
                                ->first();

        if(!isset($crmRoleIsActive)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'is_active_crm',
                'label' => 'Crm Role Is Active',
                'table_name' => 'crm_roles',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $crmRoleIsSync = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'crm_sync')
                                ->first();

        if(!isset($crmRoleIsSync)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'crm_sync',
                'label' => 'Crm Role Is Sync',
                'table_name' => 'crm_sync',
                'column_type' => 'Switch',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $maxDiscountAllowed = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'max_discount_allowed')
                                ->first();

        if(!isset($maxDiscountAllowed)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'max_discount_allowed',
                'label' => 'Crm Max Discount Allowed',
                'table_name' => 'crm_roles',
                'column_type' => 'Price',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $crmRoleCreatedBy = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'created_by')
                                ->first();

        if(!isset($crmRoleCreatedBy)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'created_by',
                'label' => 'Crm Role Created By',
                'table_name' => 'crm_roles',
                'column_type' => "Number",
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        $crmRoleUpdatedBy = CompanyField::where('company_id', 0)
                                ->where('table_name', 'crm_roles')
                                ->where('column_name', 'updated_by')
                                ->first();

        if(!isset($crmRoleUpdatedBy)){
            CompanyField::create([
                'company_id' => 0,
                'column_name' => 'updated_by',
                'label' => 'Crm Role Updated By',
                'table_name' => 'crm_roles',
                'column_type' => 'Number',
                'options' => [],
                'field_type' => 'standard'
            ]);
        }

        return true;
    }
}
