<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Expense;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Expense;

class ShowReceiptController extends Controller
{
    /**
     * Retrieve details of an expense receipt from storage.
     *
     * @param   \Xcelerate\Models\Expense $expense
     * @return  \Illuminate\Http\JsonResponse
     */
    public function __invoke(Expense $expense)
    {
        $this->authorize('view', $expense);

        if ($expense) {
            $media = $expense->getFirstMedia('receipts');

            if ($media) {
                return response()->file($media->getPath());
            }

            return respondJson('receipt_does_not_exist', 'Receipt does not exist.');
        }
    }
}
