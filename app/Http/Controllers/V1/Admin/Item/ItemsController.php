<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Item;

use Aws\Batch\BatchClient;
use Illuminate\Bus\Batch;
use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests;
use Xcelerate\Http\Requests\DeleteItemsRequest;
use Xcelerate\Http\Resources\ItemResource;
use Xcelerate\Models\Company;
use Xcelerate\Models\BatchUpload;
use Xcelerate\Models\BatchUploadRecord;
use Xcelerate\Models\Item;
use Xcelerate\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    /**
     * Retrieve a list of existing Items.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Item::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $items = Item::whereCompany()
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->applyFilters($request->all())
            ->select('items.*', 'units.name as unit_name')
            ->latest()
            ->paginateData($limit);

        return (ItemResource::collection($items))
            ->additional(['meta' => [
                'tax_types' => TaxType::whereCompany()->latest()->get(),
                'item_total_count' => Item::whereCompany()->count(),
            ]]);
    }

    /**
     * Retrieve a list of existing Items.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allItems(Request $request)
    {
        $this->authorize('viewAny', Item::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $items = Item::applyFilters($request->all())
            ->select('items.*')
            ->where('company_id', $request->company_id)
            ->latest()
            ->paginateData($limit);

        return response()->json($items, 200);
    }

    /**
     * Create Item.
     *
     * @param  Xcelerate\Http\Requests\ItemsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\ItemsRequest $request)
    {
        $this->authorize('create', Item::class);

        $item = Item::createItem($request);

        return new ItemResource($item);
    }

    /**
     * get an existing Item.
     *
     * @param  Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Item $item)
    {
        $this->authorize('view', $item);

        return new ItemResource($item);
    }

    /**
     * Update an existing Item.
     *
     * @param  Xcelerate\Http\Requests\ItemsRequest $request
     * @param  \Xcelerate\Models\Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Requests\ItemsRequest $request, Item $item)
    {
        $this->authorize('update', $item);

        $item = $item->updateItem($request);

        return new ItemResource($item);
    }

    /**
     * Delete a list of existing Items.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteItemsRequest $request)
    {
        $this->authorize('delete multiple items');

        Item::destroy($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }

    public function getZohoProducts(){
        $response = [];
        $items = Item::get();
        foreach($items as $item){
            $zoho_products = [];
            $zoho_products['id'] = $item->id;
            $zoho_products['name'] = $item->name;
            $zoho_products['description'] = $item->description;
            $zoho_products['price'] = $item->price;
            $zoho_products['company_id'] = $item->company_id;
            $zoho_products['unit_id'] = $item->unit_id;
            $zoho_products['created_time'] = $item->created_time;
            $zoho_products['updated_time'] = $item->updated_time;
            $zoho_products['creator_id'] = $item->creator_id;
            $zoho_products['currency_id'] = $item->currency_id;
            $zoho_products['currency_symbol'] = $item->currency_symbol;
            $zoho_products['tax_per_item'] = $item->tax_per_item;
            $zoho_products['price_aed'] = $item->price_aed;
            $zoho_products['price_saarc'] = $item->price_saarc;
            $zoho_products['price_us'] = $item->price_us;
            $zoho_products['price_row'] = $item->price_row;
            $zoho_products['is_sync'] = $item->is_sync;
            $zoho_products['zoho_crm_id'] = $item->zoho_crm_id;
            $zoho_products['sync_date_time'] = $item->sync_date_time;
            $zoho_products['item_code'] = $item->item_code;

            $response[] = $zoho_products;
        }

        return $response;
    }

    public function importFile(Request $request){
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv,txt',
        ]);

        $submittedFile = $request->file('import_file');
        $originalName = $submittedFile->getClientOriginalName();
        $extension = $submittedFile->getClientOriginalExtension();
        $uploadedFile = $submittedFile->storeAs('uploads', time().'_'.$originalName, 'public');
        
        $mappingRows = [];
        $mappingRows['uploaded_file'] = $uploadedFile;

        if($extension == 'csv'){
            if(file_exists(base_path().'/storage/app/public/'.$uploadedFile)){
                $file_path = fopen(base_path().'/storage/app/public/'.$uploadedFile, 'r');
                $csvHeaders = [];
                $csv_rows = [];
                $index_row = 0;
                while ($row = fgetcsv($file_path, null, ',')) {
                    if($index_row == 0){
                        $csvHeaders = $row;
                    }
                    $csv_rows[] = $row;
                    $index_row++;
                }
                fclose($file_path);
    
                if (array_key_exists(0, $csv_rows)) {
                    unset($csv_rows[0]);
                    array_values($csv_rows);
                }
                $mappingRows['file_rows'] = $csv_rows;
    
                $tableName = 'items';
                $tableColumns = DB::getSchemaBuilder()->getColumnListing($tableName);  
                $tableColumns = array_values(array_diff($tableColumns, [
                    "id", 
                    "created_at", 
                    "updated_at", 
                    "created_time",
                    "updated_time",
                    "creator_id",
                    "is_sync",
                    "sync_date_time",
                    "is_deleted",
                ]));
    
                $mappingRows['table_columns'] = $tableColumns;
                $mappingRows['file_headers'] = $csvHeaders;
            }
        }

        return response()->json(['mapping_rows' => $mappingRows], 200);
    }

    public function processFile(Request $request){
        $company =  $company = Company::find($request->header('company'));
        $tableColumns = isset($request->params['table_columns']) ? $request->params['table_columns'] : NULL;
        $csvHeaders = isset($request->params['file_headers']) ? $request->params['file_headers'] : NULL;
        $mappedColumns = isset($request->params['mapped_columns']) ? $request->params['mapped_columns'] : NULL;
        $uploadedFile = isset($request->params['uploaded_file']) ? $request->params['uploaded_file'] : NULL;

        if(file_exists(base_path().'/storage/app/public/'.$uploadedFile)){
            $file_path = fopen(base_path().'/storage/app/public/'.$uploadedFile, 'r');
            $csv_rows = [];
            $index_row = 0;
            while ($row = fgetcsv($file_path, null, ',')) {
                if($index_row !== 0){
                    $csv_rows[] = $row;
                }
                $index_row++;
            }
            fclose($file_path);
    
            $mappedFields = [];
            foreach($csvHeaders as $key => $value){
                $mappedFields[$value] = $mappedColumns[$key];
            }
    
            $batchUpload = new BatchUpload();
            $batchUpload->company_id = $company->id;
            $batchUpload->file_name = $uploadedFile;
            $batchUpload->status = 'uploaded';
            $batchUpload->model = 'ITEMS';
            $batchUpload->mapped_fields = json_encode($mappedFields, true);
            $batchUpload->save();
    
            $lastBatchId = $batchUpload->id;
            $batchUpload->name = 'batch_no_'.$lastBatchId;
            $batchUpload->update();
    
            $dataSet = [];
            foreach($csv_rows as $key => $value){
                $eachRow = [];
                foreach($value as $eachValueKey => $eachRowValue){
                    if(isset($mappedColumns[$eachValueKey]) && isset($eachRowValue)){
                        $eachRow[$mappedColumns[$eachValueKey]] = $eachRowValue;
                    }
                }
                if(count($eachRow) > 0){
                    $batchUploadRecord = new BatchUploadRecord();
                    $batchUploadRecord->batch_id = $lastBatchId;
                    $batchUploadRecord->row_data = json_encode($eachRow, true);
                    $batchUploadRecord->status = '';
                    $batchUploadRecord->process_counter = 1;
                    $batchUploadRecord->save();
                }
                $dataSet[] = $eachRow;
            }
    
            return response()->json([
                'type' => 'success', 
                'message' => 'File Uploaded. Please wait while we process the uploaded file.'
            ], 200);
        }
    }
}
