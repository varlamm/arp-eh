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

        $usersQuery = User::applyFilters($request->all())
            ->select('users.*') 
            ->where('users.id', '<>', $user->id)
            ->where('users.role', '<>', 'super admin');

        if ($user->role !== 'super admin') {
            $userCompanies = $user->companies()->get()->toArray();
            
            if (count($userCompanies) > 0) {
                $userCompany = $userCompanies[0]['id'];

                $usersQuery->join('user_company', function ($join) use ($userCompany) {
                    $join->on('users.id', '=', 'user_company.user_id')
                        ->where('user_company.company_id', '=', $userCompany);
                });
            }
        }

        $users = $usersQuery->orderBy('users.created_at', 'desc')->paginate($limit);

        return UserResource::collection($users)
            ->additional(['meta' => [
                'user_total_count' => $users->total(),
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
