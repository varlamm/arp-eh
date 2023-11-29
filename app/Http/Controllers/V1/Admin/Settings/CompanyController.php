<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\AvatarRequest;
use Xcelerate\Http\Requests\CompanyLogoRequest;
use Xcelerate\Http\Requests\TransparentLogoRequest;
use Xcelerate\Http\Requests\CompanyRequest;
use Xcelerate\Http\Requests\ProfileRequest;
use Xcelerate\Http\Resources\CompanyResource;
use Xcelerate\Http\Resources\UserResource;
use Xcelerate\Models\Company;
use Xcelerate\Models\CrmConnector;
use Illuminate\Http\Request;
use Xcelerate\Models\CompanySetting;

class CompanyController extends Controller
{
    /**
     * Retrive the Admin account.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Update the Admin profile.
     * Includes name, email and (or) password
     *
     * @param  \Xcelerate\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Update Admin Company Details
     * @param \Xcelerate\Http\Requests\CompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompany(CompanyRequest $request)
    {
        $company = Company::find($request->header('company'));

        $this->authorize('manage company', $company);

        $company->update($request->getCompanyPayload());

        $company->address()->updateOrCreate(['company_id' => $company->id], $request->address);

        return new CompanyResource($company);
    }

    /**
     * Upload the company logo to storage.
     *
     * @param  \Xcelerate\Http\Requests\CompanyLogoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadCompanyLogo(CompanyLogoRequest $request)
    {
        $company = Company::find($request->header('company'));

        $this->authorize('manage company', $company);

        $data = json_decode($request->company_logo);

        if (isset($request->is_company_logo_removed) && (bool) $request->is_company_logo_removed) {
            $company->clearMediaCollection('logo');
        }
        if ($data) {
            $company = Company::find($request->header('company'));

            if ($company) {
                $company->clearMediaCollection('logo');

                $company->addMediaFromBase64($data->data)
                    ->usingFileName($data->name)
                    ->toMediaCollection('logo');
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Upload the transparent logo to storage.
     *
     * @param  \Xcelerate\Http\Requests\TransparentLogoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTransparentLogo(TransparentLogoRequest $request){
        $company = Company::find($request->header('company'));

        $this->authorize('manage company', $company);

        $data = json_decode($request->transparent_logo);

        if (isset($request->is_transparent_logo_removed) && (bool) $request->is_transparent_logo_removed) {
            $company->clearMediaCollection('transparent_logo');
        }
        if ($data) {
            $company = Company::find($request->header('company'));

            if ($company) {
                $company->clearMediaCollection('transparent_logo');

                $company->addMediaFromBase64($data->data)
                    ->usingFileName($data->name)
                    ->toMediaCollection('transparent_logo');
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Upload the Admin Avatar to public storage.
     *
     * @param  \Xcelerate\Http\Requests\AvatarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(AvatarRequest $request)
    {
        $user = auth()->user();

        if (isset($request->is_admin_avatar_removed) && (bool) $request->is_admin_avatar_removed) {
            $user->clearMediaCollection('admin_avatar');
        }
        if ($user && $request->hasFile('admin_avatar')) {
            $user->clearMediaCollection('admin_avatar');

            $user->addMediaFromRequest('admin_avatar')
                ->toMediaCollection('admin_avatar');
        }

        if ($user && $request->has('avatar')) {
            $data = json_decode($request->avatar);
            $user->clearMediaCollection('admin_avatar');

            $user->addMediaFromBase64($data->data)
                ->usingFileName($data->name)
                ->toMediaCollection('admin_avatar');
        }

        return new UserResource($user);
    }

    public function crmConfig(Request $request){
        $company = Company::find($request->header('company'));

        $crmData = $request->crm;
        $mode = $request->mode;

        $crmConnector = new CrmConnector();
        $connectorResponse = $crmConnector->connectCrm($company->id, $crmData, $mode);
        return $connectorResponse;
    }

    public function fetchCrmSyncs(Request $request){
        $company = Company::find($request->header('company'));
        $crm = $request->crm;

        if($crm !== 'none' || $crm !== null){
            $crmSyncs = CompanySetting::getSettings(['crm_sync_items', 'crm_sync_users', 'crm_sync_roles'], $company->id);
            if(isset($crmSyncs)){
                if(count($crmSyncs) > 0){
                    $responseData = [];
                    $crmSyncsData = $crmSyncs->toArray();
                    $index = 1;
                    foreach($crmSyncsData as $key => $value ){
                        $syncData = [];
                        $syncData['id'] = $index;
                        $syncData['name'] = ucfirst(str_replace('crm_sync_', '', $key));
                        $syncData['value'] = $value;
                        $responseData[] = $syncData;
                        $index++;
                    }

                    return response()->json($responseData);
                }
            }
        }
    }

    public function crmSyncs(Request $request){
        $company = Company::find($request->header('company'));

        $crm = $request->params['crm'];
        $cmrSyncName = $request->params['name'];
        if($crm !== 'none' && !empty($cmrSyncName)){
            $option = 'crm_sync_'.strtolower($cmrSyncName);
            $getSettings = CompanySetting::getSetting($option, $company->id);
            if(isset($getSettings)){
                $key = $option;
                if($getSettings === 'Yes'){
                    $value = 'No';
                    CompanySetting::setSettings([$key => $value], $company->id);
                }
                else if($getSettings === 'No'){
                    $value = 'Yes';
                    CompanySetting::setSettings([$key => $value], $company->id);
                }

                return response()->json([
                    'success' => true
                ]);
            }
        }
    }

    public function companySettingsByDomain(Request $request) {
        $response = [];

        $response['primary_color'] = "#5851d8";
        $response['secondary_color'] = "#8a85e4";
        $response['login_page_heading'] = "Simple Invoicing for Individuals Small Businesses";
        $response['login_page_description'] = "Xcelerate helps you track expenses, record payments & generate beautiful invoices & estimates.";
        $response['tagline_text'] = "One stop invoicing solution";

        if(isset($request->sub_domain_url)){
            $subDomainUrl = $request->sub_domain_url;
            $getCompany = CompanySetting::where('option', 'sub_domain_url')
                            ->where('value', $subDomainUrl)
                            ->first();

            if(isset($getCompany)){
                $companyId = $getCompany->company_id;
                $company = Company::where('id', $companyId)->first();
                $companySettings = $getCompany::getAllSettings($companyId)->toArray();

                $response['logo'] = isset($company->logo) ? $company->logo : null;
                $response['transparent_logo'] = isset($company->transparent_logo) ? $company->transparent_logo : null;
                
                $response['primary_color'] = isset($companySettings['primary_color']) ? $companySettings['primary_color'] : null;
                $response['secondary_color'] = isset($companySettings['secondary_color']) ? $companySettings['secondary_color'] : null;
                $response['login_page_heading'] = isset($companySettings['login_page_heading']) ? $companySettings['login_page_heading'] : null;
                $response['login_page_description'] = isset($companySettings['login_page_description']) ? $companySettings['login_page_description'] : null;
                $response['tagline_text'] = isset($companySettings['tagline_text']) ? $companySettings['tagline_text'] : null;
            }
        }
        
        return response()->json($response);
    }
}
