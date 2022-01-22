<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    protected $data = [];

    /**
     * @throws AuthorizationException
     */
    public function all(): JsonResponse
    {
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('roles.index', [$user, 'id']);
        $roles = Role::all();


        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'roles' => $roles
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }
}
