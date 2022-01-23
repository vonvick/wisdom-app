<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;

class PostController extends Controller
{

    protected array $data = [];
    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    /**
     * @throws AuthorizationException
     */
    public function index(PostRequest $request): JsonResponse
    {
        $limit = $request->query('per_page', 15);
        $posts_cursor = Post::orderBy('created_at', 'desc')->paginate($limit);
        $posts_cursor->load('tags');

        return response()->json(['data' => $posts_cursor, 'status' => true, 'code' => 200, 'err' => null], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(PostRequest $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('posts.create', [$this->auth_user, 'id']);

        $post = new Post;
        $request->merge(['user_id' => $this->auth_user->id]);
        $post = Post::create($request->all());

        $tag_ids = $this->_get_post_tags($request);
        $post->tags()->sync($tag_ids);
        $post->load('tags');

        $this->data = ['status' => true, 'code' => 201, 'data' => $post, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->load('tags');

        $this->data = ['status' => true, 'code' => 200, 'data' => $post, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('posts.update', [$this->auth_user, 'id']);

        $post = Post::findOrFail($id);
        $request->merge(['user_id' => $this->auth_user->id]);
        $post->save($request->all());
        $tag_ids = $this->_get_post_tags($request);
        $post->tags()->sync($tag_ids);
        $post->load('tags');

        $this->data = [ 'status' => true, 'code' => 200, 'data' => $post, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('posts.delete', [$this->auth_user, 'id']);

        $post = Post::findOrFail($id);
        $post->delete();

        $this->data = ['status' => true, 'code' => 204, 'data' => [], 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    private function _get_post_tags(PostRequest $request) {
        $request_tags = $request->post('tags');

        $tag_ids = [];

        if (is_array($request_tags) && count($request_tags) > 0) {
            foreach ($request_tags as $tag) {
                $saved_tag = Tag::firstOrCreate(['name' => $tag]);
                $tag_ids[] = $saved_tag->id;
            }

        }

        return $tag_ids;
    }
}
