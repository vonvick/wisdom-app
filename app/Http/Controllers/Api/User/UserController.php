<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @var array
     */
    private $data;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        $this->data = [
            'data' => $users
        ];

        return response()->json($this->data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Request $request): JSONResponse
    {
        $user = User::find($request->id);
        $this->authorize('users.create', [$user, 'id']);
        $user->load('role');

        $this->data = [
            'data' => $user
        ];

        return response()->json($this->data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(UserRequest $request): JsonResponse
    {
        $user = User::find($request->id);
        $this->authorize('users.update', [$user, 'id']);
        $user->fill($request);
        $user->load('role');

        $this->data = [
            'data' => $user
        ];

        return response()->json($this->data, 200);
    }
}
