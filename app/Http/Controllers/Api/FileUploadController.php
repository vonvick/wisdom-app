<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Gallery;
use App\Models\Media;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MediaRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use function response;

class FileUploadController extends Controller
{
    protected array $data = [];
    protected ?\Illuminate\Contracts\Auth\Authenticatable $auth_user;

    public function handleMediaUpload(Request $request): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('media.upload', [$this->auth_user, 'id']);
        $uploaded = [];
        $gallery = Gallery::findOrFail($request->input('gallery_id'));

        $folder = $gallery->slug;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $file_name = $file->getClientOriginalName();
                $image_result = $file->storeOnCloudinaryAs($folder, $file_name);
                $image_path = $image_result->getSecurePath();
                $image_public_id = $image_result->getPublicId();
                $now = Carbon::now('utc')->toDateTimeString();

                $uploaded[] = [
                    'title' => $file_name,
                    'public_id' => $image_public_id,
                    'gallery_id' => $gallery->id,
                    'media_url' => $image_path,
                    'user_id' => $this->auth_user->id,
                    'description' => $request->input('description') || 'description',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

        }

        $response = Media::insert($uploaded);

        $this->data = ['status' => true, 'code' => 201, 'data' => $response, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(MediaRequest $request, int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('media.update', [$this->auth_user, 'id']);

        $media = Media::findOrFail($id);
        $media->save($request->all());

        $this->data = [ 'status' => true, 'code' => 200, 'data' => $media, 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(MediaRequest $request, int $id): JsonResponse
    {
        $this->auth_user = Auth::user();
        $this->authorize('media.delete', [$this->auth_user, 'id']);

        $media = Media::findOrFail($id);
        cloudinary()->uploadApi()->destroy($media->public_id);
        $media->delete();

        $this->data = [ 'status' => true, 'code' => 200, 'data' => [], 'err' => null];

        return response()->json($this->data, $this->data['code']);
    }
}
