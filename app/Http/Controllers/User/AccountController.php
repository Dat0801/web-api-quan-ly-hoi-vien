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
use App\Traits\ApiResponse;

class AccountController extends Controller
{
    use ApiResponse;

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

        return $this->success(UserResource::collection($users), 'Lấy danh sách tài khoản thành công.');
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return $this->success(new UserResource($user), 'Tài khoản đã được tạo thành công.', Response::HTTP_CREATED);
    }

    public function show(User $account)
    {
        return $this->success(new UserResource($account), 'Lấy thông tin tài khoản thành công.');
    }

    public function update(UpdateUserRequest $request, User $account)
    {
        $this->userService->updateUser($account->id, $request->validated());

        return $this->success(new UserResource($account), 'Tài khoản đã được cập nhật thành công.');
    }

    public function destroy(User $account)
    {
        $this->userService->deleteUser($account->id);

        return $this->success(null, 'Tài khoản đã được xóa thành công.');
    }
}
