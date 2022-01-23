<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use function response;

class RoleController extends Controller
{
    protected array $data = [];

    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    /**
     * @throws AuthorizationException
     */
    public function all(): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('roles.index', [$this->auth_user, 'id']);
        $roles = Role::all();


        $this->data = ['status' => true, 'code' => 200, 'data' => $roles, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }
}
