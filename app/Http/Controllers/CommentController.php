<?php

namespace App\Http\Controllers;

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

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
    }

    public function index($blogId)
    {
        $comments = Comment::where('blog_id', $blogId)->with('user')->get();
        return response()->json($comments);
    }
}

