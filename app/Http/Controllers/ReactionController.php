<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;


class ReactionController extends Controller
{
    public function index($blogId)
    {
        $reactions = Reaction::where('blog_id', $blogId)->with('user')->get();
        return response()->json($reactions);
    }
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $reaction = new Reaction();
        $reaction->user_id = Auth::id();
        $reaction->blog_id = $blogId;
        $reaction->type = $request->type;
        $reaction->save();

        return response()->json(['message' => 'reaction added successfully', 'reaction' => $reaction], 201);
    }
}
