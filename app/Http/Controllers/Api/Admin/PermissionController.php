<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    protected $data = [];

    /**
     * @throws AuthorizationException
     */
    public function all(): JsonResponse
    {
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('permissions.index', [$user, 'id']);
        $roles = Permission::all();


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

    /**
     * @throws AuthorizationException
     */
    public function create(PermissionRequest $request): JsonResponse
    {
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('permissions.create', [$user, 'id']);

        $permission = new Permission;
        $permission->name = $request->post('name');
        $slug = $request->post('slug');
        preg_match('/[a-z]+\.[a-z]+/i', $slug, $match);

        if ($match[0] !== $slug) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'err' => 'Slug does not match format for saving'
            ]);
        }
        $permission->slug = $slug;
        $permission->description = $request->post('description');
        $permission->active = $request->post('active');

        $permission->save();

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'user' => $permission
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function assign(Request $request): JsonResponse
    {
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('permissions.create', [$user, 'id']);

        $permission = Permission::where('slug', $request->slug)->firstOrFail();

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->attach($permission);

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'role' => $role
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function unassign(Request $request): JsonResponse
    {
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('permissions.create', [$user, 'id']);

        $permission = Permission::where('slug', $request->slug)->firstOrFail();

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->detach($permission);

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'role' => $role
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }
}
