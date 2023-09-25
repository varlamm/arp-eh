<?php

namespace Crater\Http\Controllers\V1\Admin\Item;

use Crater\Http\Controllers\Controller;
use Crater\Http\Requests;
use Crater\Http\Requests\DeleteItemsRequest;
use Crater\Http\Resources\ItemResource;
use Crater\Models\Item;
use Crater\Models\TaxType;
use Illuminate\Http\Request;

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
     * Create Item.
     *
     * @param  Crater\Http\Requests\ItemsRequest $request
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
     * @param  Crater\Http\Requests\ItemsRequest $request
     * @param  \Crater\Models\Item $item
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
}
