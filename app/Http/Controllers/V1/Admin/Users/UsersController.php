<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Users;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\DeleteUserRequest;
use Xcelerate\Http\Requests\UserRequest;
use Xcelerate\Http\Resources\UserResource;
use Xcelerate\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $user = $request->user();

        $users = User::applyFilters($request->all())
        ->where('id', '<>', $user->id)
        ->latest()
        ->paginate($limit);

        if($user->role !== 'super admin'){
            $userCompanies = $user->companies()->get()->toArray();
            if(count($userCompanies) > 0){
                $userCompany = $userCompanies[0]['id'];

                $users = User::applyFilters($request->all())
                            ->join('user_company', 'users.id', '=', 'user_company.user_id')
                            ->where('users.id', '<>', $user->id)
                            ->where('role', '<>', 'super admin')
                            ->where('user_company.company_id', '=', $userCompany)
                            ->select('users.*')
                            ->latest()
                            ->paginate($limit);
            }
        }

        return UserResource::collection($users)
            ->additional(['meta' => [
                'user_total_count' => User::count(),
            ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allUsers(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $limit = $request->has('limit') ? $request->limit : 10;

        $user = $request->user();

        $users = User::applyFilters($request->all())
            ->where('id', '<>', $user->id)
            ->latest()
            ->paginate($limit);

	    return response()->json($users, 200);

    }

	


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::createFromRequest($request);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Xcelerate\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @param  \Xcelerate\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->updateFromRequest($request);

        return new UserResource($user);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteUserRequest $request)
    {
        $this->authorize('delete multiple users', User::class);

        if ($request->users) {
            User::deleteUsers($request->users);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
