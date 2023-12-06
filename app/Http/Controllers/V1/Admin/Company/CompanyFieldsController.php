<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Company;

use Illuminate\Http\Request;
use Xcelerate\Http\Requests\CompanyFieldRequest;
use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Resources\CompanyFieldResource;
use Xcelerate\Models\CompanyField;

class CompanyFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $this->authorize('viewAny', CompanyField::class);

        $limit = $request->has('limit') ? $request->limit : 5;

        $companyFields = CompanyField::applyFilters($request->all())
            ->whereCompany()
            ->latest()
            ->paginateData($limit);
        
        return CompanyFieldResource::collection($companyFields);
    }
}
