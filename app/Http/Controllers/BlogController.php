<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $languageCode = $request->header('Language-Code');

        if (!$languageCode) {
            return response()->json(['message' => 'Language code not provided'], 400);
        }

        $language = Language::where('code', $languageCode)->first();

        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $blogs = BlogTranslation::where('language_id', $language->id)
            ->with('blog.user')
            ->get()
            ->map(function ($translation) {
                return [
                    'id' => $translation->blog->id,
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'content' => $translation->content,
                    'author' => $translation->blog->user->name,
                    'language' => $translation->language->code,
                ];
            });

        return response()->json(['blogs' => $blogs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.language_code' => 'required|string|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.subtitle' => 'string|max:255|nullable',
            'translations.*.content' => 'required|string',
        ]);

        $blog = Auth::user()->blogs()->create();

        foreach ($request->translations as $translation) {
            $language = Language::where('code', $translation['language_code'])->first();
            $blog->translations()->create([
                'language_id' => $language->id,
                'title' => $translation['title'],
                'subtitle' => $translation['subtitle'],
                'content' => $translation['content']
            ]);
        }

        return response()->json($blog->load('translations.language'), 201);
    }

    public function show($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            
            // Adjust the response structure as needed
            return response()->json([
                'id' => $blog->id,
                'title' => $blog->title,
                'subtitle' => $blog->subtitle,
                'content' => $blog->content,
                'user_id' => $blog->user_id,
                // Add any other fields you want to return
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'translations' => 'required|array',
            'translations.*.language_code' => 'required|string|exists:languages,code',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.subtitle' => 'string|max:255|nullable',
            'translations.*.content' => 'required|string',
        ]);

        $blog = Blog::find($id);
        $blog->translations()->delete();

        foreach ($request->translations as $translation) {
            $language = Language::where('code', $translation['language_code'])->first();
            $blog->translations()->create([
                'language_id' => $language->id,
                'title' => $translation['title'],
                'subtitle' => $translation['subtitle'],
                'content' => $translation['content']
            ]);
        }

        return response()->json($blog->load('translations.language'), 200);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        // Get the language code from the header
        $languageCode = $request->header('Language-Code');

        // Log the received language code
        Log::info('Language code received: ' . $languageCode);

        // Validate the presence of language code
        if (!$languageCode) {
            return response()->json(['message' => 'Language code not provided'], 400);
        }

        // Find the language by code
        $language = Language::where('code', $languageCode)->first();

        // Log the found language or error
        if (!$language) {
            Log::error('Language not found for code: ' . $languageCode);
            return response()->json(['message' => 'Language not found'], 404);
        } else {
            Log::info('Language found: ' . $language->code);
        }

        // Get the search query
        $query = $request->query('q');

        // Log the received search query
        Log::info('Search query received: ' . $query);

        // Validate the presence of a search query
        if (!$query) {
            return response()->json(['message' => 'Search query not provided'], 400);
        }

        // Perform the search
        $blogTranslations = BlogTranslation::where('language_id', $language->id)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('subtitle', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->with('blog.user')
            ->get();

        // Log the search results
        Log::info('Blog translations found: ' . $blogTranslations->count());

        // Check if any results were found
        if ($blogTranslations->isEmpty()) {
            return response()->json(['message' => 'No blogs found for the specified language and query'], 404);
        }

        // Transform the data to the desired structure
        $blogs = $blogTranslations->map(function ($translation) {
            return [
                'id' => $translation->blog->id,
                'title' => $translation->title,
                'subtitle' => $translation->subtitle,
                'content' => $translation->content,
                'author' => $translation->blog->user->name,
                'language' => $translation->language->code,
            ];
        });

        return response()->json(['blogs' => $blogs]);
    }
}
