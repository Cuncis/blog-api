<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()->latest()->get();

        return $this->success(CommentResource::collection($comments), 'Comments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $post->comments()->create($request->validated());
        return $this->success(CommentResource::make($comment), 'Comment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        return $this->success(CommentResource::make($comment), 'Comment retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());
        return $this->success(CommentResource::make($comment), 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return $this->success(null, 'Comment deleted successfully.', 204);
    }
}
