<?php

namespace Xcelerate\Http\Controllers\V1\Admin\RecurringInvoice;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\RecurringInvoiceRequest;
use Xcelerate\Http\Resources\RecurringInvoiceResource;
use Xcelerate\Models\RecurringInvoice;
use Illuminate\Http\Request;

class RecurringInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', RecurringInvoice::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $recurringInvoices = RecurringInvoice::whereCompany()
            ->applyFilters($request->all())
            ->paginateData($limit);

        return (RecurringInvoiceResource::collection($recurringInvoices))
            ->additional(['meta' => [
                'recurring_invoice_total_count' => RecurringInvoice::whereCompany()->count(),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecurringInvoiceRequest $request)
    {
        $this->authorize('create', RecurringInvoice::class);

        $recurringInvoice = RecurringInvoice::createFromRequest($request);

        return new RecurringInvoiceResource($recurringInvoice);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Xcelerate\Models\RecurringInvoice  $recurringInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(RecurringInvoice $recurringInvoice)
    {
        $this->authorize('view', $recurringInvoice);

        return new RecurringInvoiceResource($recurringInvoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Xcelerate\Models\RecurringInvoice  $recurringInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(RecurringInvoiceRequest $request, RecurringInvoice $recurringInvoice)
    {
        $this->authorize('update', $recurringInvoice);

        $recurringInvoice->updateFromRequest($request);

        return new RecurringInvoiceResource($recurringInvoice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Xcelerate\Models\RecurringInvoice  $recurringInvoice
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $this->authorize('delete multiple recurring invoices');

        RecurringInvoice::deleteRecurringInvoice($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}
