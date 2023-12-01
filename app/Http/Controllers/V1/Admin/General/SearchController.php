<?php

namespace Xcelerate\Http\Controllers\V1\Admin\General;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Models\Customer;
use Xcelerate\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $customers = Customer::applyFilters($request->only(['search']))
            ->whereCompany()
            ->latest()
            ->paginate(10);

        if ($user->isOwner()) {
            $users = User::applyFilters($request->only(['search']))
                ->latest()
                ->paginate(10);
        }

        return response()->json([
            'customers' => $customers,
            'users' => $users ?? [],
        ]);
    }
}
