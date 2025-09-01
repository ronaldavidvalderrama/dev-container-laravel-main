<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Http\Requests\StorePostRequest;


class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //la mala practica porque tenemos model
        //return response()->json(DB::table('posts')->get());
        return $this->ok("todo ok, como dijo el pibe",Post::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $newPost = Post::create($data);
        return $this->ok("Todo melo melo caramelo", [$newPost]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Post::find($id);
        if($result) {
            return $this->ok("todo ok, como dijo el pibe", $result);
        } else {
            return $this->successful("todo mal, como no dijo el pibe",[], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
