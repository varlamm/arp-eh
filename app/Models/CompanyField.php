<?php

namespace Xcelerate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyField extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $dates = [
        'date',
        'date_time'
    ];

    protected $appends = [
        'defaultValue',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function scopeWhereCompany($query) {
        return $query->where('company_fields.company_id', request()->header('company'));
    }

    public function scopeWhereSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('label', 'LIKE', '%'.$search.'%')
                ->orWhere('name', 'LIKE', '%'.$search.'%');
        });
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('type')) {
            $query->whereColumnType($filters->get('type'));
        }

        if ($filters->get('search')) {
            $query->whereTableName($filters->get('search'));
        }
    }

    public function scopeWhereType($query, $type)
    {
        $query->where('company_fields.column_type', $type);
    }

    public function setTimeAnswerAttribute($value)
    {
        if ($value && $value != null) {
            $this->attributes['time'] = date("H:i:s", strtotime($value));
        }
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value);
    }

    public function getDefaultValueAttribute()
    {
        $value_type = getCompanyFieldValueKey($this->column_type);

        return $this->$value_type;
    }

    public static function createCompanyField($request){
        $data = $request->validated();
        $data[getCompanyFieldValueKey($request->column_type)] = $request->default_value;
        $data['company_id'] = $request->header('company');

        $companyFieldExist = CompanyField::where('column_name', $request->column_name)
                                        ->where('table_name', $request->table_name)
                                        ->where('company_id', $data['company_id'])
                                        ->first();

        if(!isset($companyFieldExist)){
            $createCompanyField = CompanyField::create($data);    

            $companyStandardFieldExist = CompanyField::where('column_name', $request->column_name)
                                            ->where('table_name', $request->table_name)
                                            ->where('company_id', 0)
                                            ->first();

            if(!isset($companyStandardFieldExist)){
                $data['company_id'] = 0;
                $data['column_name'] = $request->column_name;
                $data['column_type'] = $request->column_type;

                $createCompanyField = CompanyField::create($data);
            }
            return $createCompanyField;
        } else {
            return $error['error'] = 'error';
        } 
    }

    public function updateCompanyField($request){
        $data = $request->validated();
        $user = $request->user();

        unset($data['column_name'], $data['column_type']);
        $data[getCompanyFieldValueKey($request->column_type)] = $request->default_value;
        $data['creator_id'] = $user->id;
        $this->update($data);
        $updateCompanyField = $this;
        
        if($user->isSuperAdmin()){
            $companyFieldExist = CompanyField::where('column_name', $request->column_name)
                                    ->where('table_name', $request->table_name)
                                    ->where('company_id', 0)
                                    ->first();

            if(isset($companyFieldExist)){
                $data['company_id'] = 0;
                $data['column_name'] = $request->column_name;
                $data['column_type'] = $request->column_type;

                $updateCompanyField = $companyFieldExist->update($data);

                return $updateCompanyField;
            }
        }
        
        return $updateCompanyField;
    }
}
