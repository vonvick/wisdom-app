<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
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
        $member_role = Role::where('name', 'member')->first();

        $user = User::firstOrCreate(
            ['email' => $request->post('email')],
            [
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'email' => $request->post('email'),
                'password' => Hash::make($request->post('password')),
                'role_id' => $member_role->id
            ]
        );

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => $user
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }
}
