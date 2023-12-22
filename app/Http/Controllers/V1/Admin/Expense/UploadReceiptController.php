<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Expense;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\UploadExpenseReceiptRequest;
use Xcelerate\Models\Expense;

class UploadReceiptController extends Controller
{
    /**
     * Upload the expense receipts to storage.
     *
     * @param  \Xcelerate\Http\Requests\ExpenseRequest $request
     * @param  Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UploadExpenseReceiptRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $data = json_decode($request->attachment_receipt);

        if ($data) {
            if ($request->type === 'edit') {
                $expense->clearMediaCollection('receipts');
            }

            $expense->addMediaFromBase64($data->data)
                ->usingFileName($data->name)
                ->toMediaCollection('receipts');
        }

        return response()->json([
            'success' => 'Expense receipts uploaded successfully',
        ], 200);
    }
}
