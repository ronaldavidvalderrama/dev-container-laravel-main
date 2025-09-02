<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // La mala practica porque tenemos un Model.
        // return response()->json(DB::table("posts")->get());
        return $this->ok("Todo ok, como dijo el Pibe", Post::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $newPost = Post::create($data);

        if(!empty($data['category_ids'])) {
            $newPost->categories()->sync($data['category_ids']);
        }

        return $this->create("Todo melo mor", [$newPost]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Post::find($id);

        if($result) {
            return $this->ok("Todo ok, como dijo el Pibe", $result);
        } else {
            return $this->success("Todo mal, como NO dijo el Pibe", [], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, post $post)
    {
        $data = $request->validated();

        if($request->hasFile('cover_image')) {
            //BORRADO OPCIONAL
            if($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        $post->update($data);
        if(array_key_exists('category_ids', $data)) {
            $post->categories()->sync($data['category:_ids'] ?? [] );
        }
        return $this->ok("Todo melo melo meloso", [$post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post->delete();
        return $this->ok("eliminado melo melo meloso", [$post]);
    }
}