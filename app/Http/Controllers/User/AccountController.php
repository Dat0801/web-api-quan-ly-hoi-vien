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
/**
 * @OA\Tag(
 *     name="Users",
 *     description="Quản lý tài khoản người dùng"
 * )
 */

class AccountController extends Controller
{
    use ApiResponse;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lấy danh sách người dùng",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Danh sách người dùng", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User")))
     * )
     */
    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers(
            $request->get('status'),
            $request->get('role_id'),
            $request->get('search')
        );

        return $this->success(UserResource::collection($users), 'Lấy danh sách tài khoản thành công.');
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Tạo tài khoản",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 ref="#/components/schemas/StoreUserRequest", 
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tài khoản đã tạo", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return $this->success(new UserResource($user), 'Tài khoản đã được tạo thành công.', Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Lấy thông tin tài khoản",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thông tin tài khoản", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function show(User $account)
    {
        return $this->success(new UserResource($account), 'Lấy thông tin tài khoản thành công.');
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Cập nhật tài khoản",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(ref="#/components/schemas/StoreUserRequest"),
     *     @OA\Response(response=200, description="Tài khoản đã cập nhật", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function update(UpdateUserRequest $request, User $account)
    {
        $this->userService->updateUser($account->id, $request->validated());
        return $this->success(new UserResource($account), 'Tài khoản đã được cập nhật thành công.');
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Xóa tài khoản",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Tài khoản đã xóa")
     * )
     */
    public function destroy(User $account)
    {
        $this->userService->deleteUser($account->id);
        return $this->success(null, 'Tài khoản đã được xóa thành công.');
    }
}
