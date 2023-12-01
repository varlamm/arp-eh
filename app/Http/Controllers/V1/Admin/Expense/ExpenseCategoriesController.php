<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Expense;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\ExpenseCategoryRequest;
use Xcelerate\Http\Resources\ExpenseCategoryResource;
use Xcelerate\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ExpenseCategory::class);

        $limit = $request->has('limit') ? $request->limit : 5;

        $categories = ExpenseCategory::applyFilters($request->all())
            ->whereCompany()
            ->latest()
            ->paginateData($limit);

        return ExpenseCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseCategoryRequest $request)
    {
        $this->authorize('create', ExpenseCategory::class);

        $category = ExpenseCategory::create($request->getExpenseCategoryPayload());

        return new ExpenseCategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Xcelerate\Models\ExpenseCategory $category
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $category)
    {
        $this->authorize('view', $category);

        return new ExpenseCategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Xcelerate\Models\ExpenseCategory $ExpenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseCategoryRequest $request, ExpenseCategory $category)
    {
        $this->authorize('update', $category);

        $category->update($request->getExpenseCategoryPayload());

        return new ExpenseCategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Xcelerate\ExpensesCategory $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $category)
    {
        $this->authorize('delete', $category);

        if ($category->expenses() && $category->expenses()->count() > 0) {
            return respondJson('expense_attached', 'Expense Attached');
        }

        $category->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
