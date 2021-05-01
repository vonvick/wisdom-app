<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->data = [
            'status' => false,
            'code' => 401,
            'data' => null,
            'err' => [
                'code' => 1,
                'message' => 'UnAuthorized'
            ]
        ];
    }

    public function login (Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new Exception('invalid_credentials');
            }

            $this->data = [
                'status' => true,
                'code' => 200,
                'data' => [
                    '_token' => $token
                ],
                'err' => null
            ];
        } catch (Exception $e) {
            if ($e instanceof JWTException) {
                $this->data['err']['message'] = 'Could not create token';
                $this->data['code'] = 500;
            } else {
                $this->data['err']['message'] = $e->getMessage();
                $this->data['code'] = 401;
            }
        }

        return response()->json($this->data, $this->data['code']);
    }

    public function detail(): JsonResponse
    {
        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => auth()->user()
            ],
            'err' => null
        ];
        return response()->json($this->data);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'message' => 'Successfully logged out'
            ],
            'err' => null
        ];
        return response()->json($data);
    }

    public function refresh(): JsonResponse
    {
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                '_token' => auth()->refresh()
            ],
            'err' => null
        ];
        return response()->json($data, 200);
    }
}
