<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;

class PermissionController extends Controller
{
    protected array $data = [];

    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    /**
     * @throws AuthorizationException
     */
    public function all(): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('permissions.index', [$this->auth_user, 'id']);
        $permissions = Permission::all();


        $this->data = ['status' => true, 'code' => 200, 'data' => $permissions, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(PermissionRequest $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('permissions.create', [$this->auth_user, 'id']);

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

        $this->data = ['status' => true, 'code' => 200, 'data' => $permission, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function assign(Request $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('permissions.create', [$this->auth_user, 'id']);

        $permission = Permission::where('slug', $request->slug)->firstOrFail();

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->attach($permission);

        $this->data = ['status' => true, 'code' => 200, 'data' => $role, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function unassign(Request $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('permissions.create', [$this->auth_user, 'id']);

        $permission = Permission::where('slug', $request->slug)->firstOrFail();

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->detach($permission);

        $this->data = [ 'status' => true, 'code' => 200, 'data' => $role, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }
}
