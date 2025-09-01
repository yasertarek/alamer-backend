<?php

namespace App\Http\Controllers;
use App\Http\Resources\CatsResource;
use App\Models\Cats;
use Illuminate\Http\Request;

class CatsController extends Controller
{
    //
    public function index(Request $request)
    {

        $searchQuery = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $cats = Cats::query();
        if ($searchQuery) {
            $cats->where('name', 'like', "%$searchQuery%");
        }
        $cats = $cats->paginate($perPage);
        return CatsResource::collection($cats);
    }
    public function show($id)
    {
        $cat = Cats::find($id);
        if (!$cat) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($cat);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cats,name',
        ]);

        $cat = Cats::create([
            'name' => $request->name,
        ]);

        return response()->json($cat, 201);
    }
    public function update(Request $request, $id){
        $cat = Cats::find($id);
        if (!$cat) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:cats,name,'.$cat->id,
        ]);

        $cat->name = $request->name;
        $cat->save();

        return response()->json($cat);
    }
    public function destroy($id){
        $cat = Cats::find($id);
        if (!$cat) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $cat->delete();
        return response()->json(['message' => 'Category deleted']);
    }
    public function search(Request $request)
    {
        $searchQuery = $request->input('query');
        $cats = Cats::where('name', 'like', "%$searchQuery%")->get();
        return response()->json($cats);
    }
}
