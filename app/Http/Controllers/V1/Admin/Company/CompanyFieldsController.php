<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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

        $limit = $request->has('limit') ? $request->limit : 20;

        $companyFields = CompanyField::applyFilters($request->all())
            ->whereCompany()
            ->latest()
            ->paginateData($limit);
        
        return CompanyFieldResource::collection($companyFields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CompanyFieldRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyFieldRequest $request){
        $this->authorize('create', CompanyField::class);

        $companyField = CompanyField::createCompanyField($request);

        if($companyField === 'error'){
            return response()->json(['message' => 'This column already exists'], 205);
        }
        else{
            $this->alterTableColumn($request, $companyField);

            return new CompanyFieldResource($companyField);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyField $companyField){
        $this->authorize('view', $companyField);

        return new CompanyFieldResource($companyField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyFieldRequest $request, CompanyField $companyField)
    {
        $this->authorize('update', $companyField);

        $companyField->updateCompanyField($request);

        $this->alterTableColumn($request, $companyField);

        return new CompanyFieldResource($companyField);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyField $companyField){
        $this->authorize('delete', $companyField);

        $companyField->forceDelete();

        return response()->json([
            'success' => true
        ]);
    }

    public function alterTableColumn($request, $companyField){
        if(isset($companyField)){
            if(isset($companyField->table_name)){
                $table = strtolower($companyField->table_name);
                $table_column_type = getCompanyFieldValueKey($request->column_type);
                if($request->column_type == 'Price'){
                    $table_column_type = 'float';
                }
               
                $table_columns = DB::getSchemaBuilder()->getColumnListing($table);
                $table_column = $companyField->column_name;
                Schema::table($table, function (Blueprint $table) use ($table_columns, $table_column, $table_column_type) {
                    if(!in_array($table_column, $table_columns)){
                        $table->$table_column_type($table_column)->nullable();
                    }
                });
            }
        }

        return true;
    }
}