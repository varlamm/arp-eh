<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Settings;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\UpdateSettingsRequest;
use Xcelerate\Models\Company;
use Xcelerate\Models\CompanySetting;
use Illuminate\Support\Arr;

class UpdateCompanySettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\UpdateSettingsRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateSettingsRequest $request)
    {
        $company = Company::find($request->header('company'));
        $this->authorize('manage company', $company);

        $data = $request->settings;

        if (
            Arr::exists($data, 'currency') &&
            (CompanySetting::getSetting('currency', $company->id) !== $data['currency']) &&
            $company->hasTransactions()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update company currency after transactions are created.'
            ]);
        }

        CompanySetting::setSettings($data, $request->header('company'));

        return response()->json([
            'success' => true,
        ]);
    }
}
