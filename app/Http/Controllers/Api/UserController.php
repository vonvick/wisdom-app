<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function response;

class UserController extends Controller
{
    protected array $data = [];

    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    /**
     * @throws AuthorizationException
     */
    public function register (UserRequest $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('users.register', [$this->auth_user, 'id']);

        $member_role = Role::where('slug', 'member')->first();
        $password = Hash::make($request->post('password'));
        $request->merge(['role_id' => $member_role->id, 'password' => $password]);

        $user = User::create($request->all());

        $this->data = ['status' => true, 'code' => 201, 'data' => $user, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function index(UserRequest $request): JsonResponse
    {
        $limit = $request->query('per_page', 15);
        $users = User::orderBy('first_name', 'desc')->paginate($limit);

        $this->data = ['status' => true, 'code' => 200, 'data' => $users, 'err' => null];

        return response()->json($this->data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer $id
     * @return JsonResponse
     */
    public function show(int $id): JSONResponse
    {
        $user = User::find($id);
        $user->load('role');

        $this->data = ['status' => true, 'code' => 200, 'data' => $user, 'err' => null];

        return response()->json($this->data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('users.update', [$this->auth_user, 'id']);
        $user = User::findOrFail($id);

        $request->merge(['role_id' => $user->role_id]);
        $user->fill($request->all());
        $user->load('role');

        $this->data = ['status' => true, 'code' => 200, 'data' => $user, 'err' => null];

        return response()->json($this->data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function deactivate(int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('users.delete', [$this->auth_user, 'id']);
        $deactivate_user = User::findOrFail($id);
        $deactivate_user->softDelete();

        $this->data = ['status' => true, 'code' => 200, 'data' => $deactivate_user, 'err' => null];

        return response()->json($this->data, 204);
    }
}
