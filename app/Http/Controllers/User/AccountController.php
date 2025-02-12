<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers(
            $request->get('status'),
            $request->get('role_id'),
            $request->get('search')
        );

        return UserResource::collection($users);
    }

   
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'message' => 'Tài khoản đã được tạo thành công.',
            'data' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }

    
    public function show(User $account)
    {
        return new UserResource($account);
    }

    public function update(UpdateUserRequest $request, User $account)
    {
        $this->userService->updateUser($account->id, $request->validated());

        return response()->json([
            'message' => 'Tài khoản đã được cập nhật thành công.',
            'data' => new UserResource($account)
        ], Response::HTTP_OK);
    }

    public function destroy(User $account)
    {
        $this->userService->deleteUser($account->id);

        return response()->json([
            'message' => 'Tài khoản đã được xóa thành công.'
        ], Response::HTTP_OK);
    }
}
