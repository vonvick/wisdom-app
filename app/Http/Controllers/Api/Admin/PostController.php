<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    protected $data = [];

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResponse
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
        $id = Auth::id();
        $user = User::find($id);
        $this->authorize('posts.create', [$user, 'id']);

        $post = new Post;
        $post->title = $request->post('title');
        $post->slug = $request->post('slug');
        $post->content = $request->post('content');
        $post->flag = $request->post('flag');
        $post->user_id = $user->id;

        $post->save();
        $tag_ids = $this->_get_post_tags($request);
        $post->tags()->sync($tag_ids);

        $this->data = [
            'status' => true,
            'code' => 201,
            'data' => $post,
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->load('tags');

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => $post,
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PostRequest $request, int $id): JsonResponse
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $this->authorize('posts.update', [$user, 'id']);

        $post = Post::findOrFail($id);
        $post->save($request->all());
        $tag_ids = $this->_get_post_tags($request);
        $post->tags()->sync($tag_ids);

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => $post,
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $id): JsonResponse
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $this->authorize('posts.delete', [$user, 'id']);

        $post = Post::findOrFail($id);
        $post->delete();

        $this->data = [
            'status' => true,
            'code' => 204,
            'data' => [],
            'err' => null
        ];

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
