<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::latest()->get();
        return $this->success($posts, 'Posts retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());

        return $this->success($post, 'Post created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        return $this->success($post, 'Post retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return $this->success($post, 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return $this->success(null, 'Post deleted successfully', 204);
    }
}
