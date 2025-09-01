<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->blog_id = $blogId;
        $comment->content = $request->content;
        $comment->save();

        return response()->json(['message' => 'Comment added successfully', 'comment' => new CommentResource($comment)], 201);
    }

    public function index($blogId)
    {
        $comments = Comment::where('blog_id', $blogId)->with('user')->orderBy('created_at', 'desc')->paginate(5);
        return CommentResource::collection($comments);
    }
}

