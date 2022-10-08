<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    protected array $data = [];
    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    /**
     * @throws AuthorizationException
     */
    public function index(GalleryRequest $request): JsonResponse
    {
        $limit = $request->query('per_page', 15);
        $gallery_cursor = Gallery::orderBy('created_at', 'desc')->paginate($limit);

        return response()->json(['data' => $gallery_cursor, 'status' => true, 'code' => 200, 'err' => null], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(GalleryRequest $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('gallery.create', [$this->auth_user, 'id']);

        $post = new Gallery;
        $request->merge(['user_id' => $this->auth_user->id]);
        $gallery = Gallery::create($request->all());

        $this->data = ['status' => true, 'code' => 201, 'data' => $gallery, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        $gallery = Gallery::findOrFail($id);

        $this->data = ['status' => true, 'code' => 200, 'data' => $gallery, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(GalleryRequest $request, int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('gallery.update', [$this->auth_user, 'id']);

        $gallery = Gallery::findOrFail($id);
        $request->merge(['user_id' => $this->auth_user->id]);
        $gallery->save($request->all());

        $this->data = [ 'status' => true, 'code' => 200, 'data' => $gallery, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('gallery.delete', [$this->auth_user, 'id']);

        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        $this->data = ['status' => true, 'code' => 204, 'data' => [], 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }
}
