<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;
use App\Http\Resources\ReactionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ReactionController extends Controller
{
    public function index($blogId)
    {
        $reactions = Reaction::where('blog_id', $blogId)->with('user')->get();
        return response()->json($reactions);
    }
    public function store(Request $request, $blogId)
    {
        // if(!Auth::check()){
        //     response()->json(['message' => 'Unauthorized'], 401);
        // }
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
    public function update(Request $request, $blogId)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $reaction = Reaction::where('blog_id', $blogId)->where('user_id', Auth::id())->firstOrFail();
        $reaction->update([
            'type' => $request->type,
        ]);

        return response()->json([
            "message" => "تم تعديل التفاعل بنجاح",
            "reaction" => new ReactionResource($reaction)
        ]);
    }
    public function destroy(Request $request, $blogId)
    {
        $reaction = Reaction::where('blog_id', $blogId)
                            ->where('user_id', Auth::id())
                            ->first();

        if (!$reaction) {
            return response()->json(['error' => 'Reaction not found or you are not authorized to delete this reaction.'], 404);
        }

        $reaction->delete();

        return response()->json(['message' => 'Reaction deleted successfully.'], 200);
    }
}
