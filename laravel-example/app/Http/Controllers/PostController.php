<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('categories')->get();
        //use App\Http\Resources\PostResource
        return $this->success(PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $newPost = Post::create($data);

        if (!empty($data['category_ids'])) {
            $newPost->categories()->sync($data['category_ids']);
        }

        return $this->success(new PostResource($newPost), 'Post creado correctamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        //$result = Post::findOrFail($id);
        $result = Post::find($id);
        if ($result) {
            return $this->success(new PostResource($result), "Todo ok, como dijo el Pibe");
        } else {
            return $this->error("Todo mal, como NO dijo el Pibe", 404, ['id' => 'No se encontro el recurso con el id']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //use Illuminate\Support\Facades\Log;
        Log::debug('all:', $request->all());
        Log::debug('files:', array_keys($request->allFiles()));
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            //Borrado (Opcional)
            if ($post->cover_image) {
                //use Illuminate\Support\Facades\Storage;
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }
        $post->update($data);

        if (array_key_exists('category_ids', $data)) {
            $post->categories()->sync($data['category_ids'] ?? []);
        }
        return $this->success(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete(); //Soft delete
        return $this->success(null, 'Post eliminado', 204);
    }

    public function restore(int $id): JsonResponse
    {
        Log::debug('restore: ' . $id);
        $post = Post::onlyTrashed()->find($id);
        if (!$post) {
            //throw new ModelNotFoundException('Post no encontrado', 404);
            Log::debug('restore: ' . $id);
            throw new RecordsNotFoundException('Post no encontrado', 404);
        }
        Log::debug('restore: start');
        $post->restore();
        Log::debug('restore: success');
        return $this->success($post, 'Post restaurado correctamente');
    }
}