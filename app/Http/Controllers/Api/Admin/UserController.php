<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->middleware('admin.check');
    }

    public function register (UserRequest $request): JsonResponse
    {
        $member_role = Role::where('slug', 'member')->first();

        $user = new User;
        $user->first_name = $request->post('first_name');
        $user->last_name = $request->post('last_name');
        $user->email = $request->post('email');
        $user->password = Hash::make($request->post('password'));
        $user->headline = $request->post('headline');
        $user->full_description = $request->post('full_description');
        $user->role_id = $member_role->id;
        $user->phone = $request->post('phone');

        $user->save();

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'user' => $user
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(UserRequest $request): JsonResponse
    {
        $user = User::find($request->id);
        $this->authorize('users.delete', [$user, 'id']);
        $user->delete();

        $this->data = [
            'data' => $user
        ];

        return response()->json($this->data, 204);
    }
}
