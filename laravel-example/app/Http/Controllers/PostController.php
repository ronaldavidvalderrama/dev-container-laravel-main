<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
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

        return $this->successful(PostResource::collection($posts));
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

        return $this->successful("Todo melo mor", [$newPost], 'Post creado correctamente', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        //$result = Post::findOrFail($id);
        $result = Post::find($id);
        if ($result) {
            return $this->successful("Todo ok, como dijo el Pibe", $result);
        } else {
            return $this->error("Todo mal, como NO dijo el Pibe", [], 404, ['id' => 'No se encontro el recurso con el id']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        Log::debug('all:', $request->all());
        Log::debug('files:', array_keys($request->allFiles()));
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
        }

        $post->update($data);

        if (array_key_exists('category_ids', $data)) {
        }

        return $this->successful(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete(); //Soft delete
        //return response()->noContent();
        return $this->successful(null, 'post eliminado');
    }

    public function restore(int $id): JsonResponse 
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return $this->successful($post, 'Post restaurado correctamente');
    }
}