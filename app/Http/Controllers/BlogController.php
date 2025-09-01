<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogTranslation;
use App\Models\Language;
use App\Http\Resources\BlogResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

// use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar');
        $searchQuery = $request->input('search');
        $sortOrder = $request->input('sort', 'desc'); // Default to descending if not provided
        $catsFilter = $request->input('cats'); // New line to get category filter

        $language = Language::where('code', $languageCode)->firstOrFail();

        $query = Blog::with([
            'user',
            'cats',
            'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }
        ])
            ->withCount(['comments', 'reactions'])
            ->where('active', 1)
            ->select('blogs.*');

        if ($searchQuery) {
            $query->whereHas('translations', function ($q) use ($language, $searchQuery) {
                $q->where('language_id', $language->id)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', "%$searchQuery%")
                            ->orWhere('subtitle', 'like', "%$searchQuery%")
                            ->orWhere('content', 'like', "%$searchQuery%");
                    });
            });
        }

        if ($catsFilter && is_array($catsFilter)) {
            $query->whereHas('cats', function ($q) use ($catsFilter) {
                $q->whereIn('cats.id', $catsFilter);
            });
        }
        

        $query->orderBy('blogs.created_at', $sortOrder);

        $blogs = $query->paginate(10); // Adjust the pagination as needed

        return BlogResource::collection($blogs);
    }

    private static function generateSlug($title)
    {
        // $nSlug = $title.toString().toLowerCase();
        // .replace(/\s+/g, '-')           // Replace spaces with -
        // .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        // .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        // .replace(/^-+/, '')             // Trim - from start of text
        // .replace(/-+$/, '')
        // $tSlug = preg_replace('/[^A-Za-z0-9-]+/', '-', $title);

        // $slug = preg_replace('/\s+/u', '-', trim($title));
        // $slug = preg_replace('/[^\pL\pN\p{Arabic}_-]+/u', '', $slug); // Allow Arabic characters, letters, numbers, dashes, and underscores
        $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '_', $title);
        $tSlug = trim($slug, '_');
        return mb_strtolower($tSlug, 'UTF-8');
    }

    public function getSelfBlogs(Request $request)
    {
        $userId = Auth::user()->id;
        $languageCode = $request->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->firstOrFail();

        $searchQuery = $request->input('search');
        $sortOrder = $request->input('sort', 'desc'); // Default to descending if not provided
        $catsFilter = $request->input('cats'); // New line to get category filter


        $query = Blog::with([
            'user',
            'cats',
            'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }
        ])
            ->withCount(['comments', 'reactions'])
            ->select('blogs.*')->where('user_id', $userId);

        if ($searchQuery) {
            $query->whereHas('translations', function ($q) use ($language, $searchQuery) {
                $q->where('language_id', $language->id)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', "%$searchQuery%")
                            ->orWhere('subtitle', 'like', "%$searchQuery%")
                            ->orWhere('content', 'like', "%$searchQuery%");
                    });
            });
        }

        if ($catsFilter && is_array($catsFilter)) {
            $query->whereHas('cats', function ($q) use ($catsFilter) {
                $q->whereIn('cats.id', $catsFilter);
            });
        }
        

        $query->orderBy('blogs.created_at', $sortOrder);

        $blogs = $query->paginate(10); // Adjust the pagination as needed

        return BlogResource::collection($blogs);
        

        // $blogs = Blog::with([
        //     'translations' => function ($query) use ($language) {
        //         $query->where('language_id', $language->id);
        //     },
        //     'cats',
        // ])
        // ->where('user_id', $userId)
        // ->paginate(10);

        // return BlogResource::collection($blogs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255|unique:blog_translations,title',
            'translations.*.subtitle' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
            'cats' => 'required|array', // array of category IDs
            'cats.*' => 'exists:cats,id',
        ]);

        if ($validator->fails()) {
            return response()->json(["message"=> $validator->errors()], 422);
        }

        $picturePath = null;
        if (request()->hasFile('picture')) {
            $picturePath = request()->file('picture')->store('blog_pictures', 'public');
        }

        $blog = Blog::create([
            'user_id' => Auth::user()->user_id ?? Auth::id(),
            'picture' => $picturePath 
            ? asset('storage/' . $picturePath) 
            : null,
        ]);

        $blog->cats()->attach($request->input('cats'));

        foreach ($request->translations as $translation) {
            // $titleExists = BlogTranslation::where('title', $translation['title'])->exists();
            // if ($titleExists) {
            //     return response()->json(['error' => 'The blog title has already been taken.'], 422);
            // }
            $blogTranslationSlug = static::generateSlug($translation['title']);
            // Check if slug is unique
            $slugExists = BlogTranslation::where('slug', $blogTranslationSlug)->exists();
            if ($slugExists) {
                return response()->json(['error' => 'The blog slug has already been taken.'], 422);
            }

            // $language = Language::where('code', $translation['language_code'])->first();

            $translation['blog_id'] = $blog->id;
            $translation['slug'] = $blogTranslationSlug;
            BlogTranslation::create($translation);
        }

        return response()->json(new BlogResource($blog->load(['translations', 'cats'])), 201);
    }

    public function show($slug, Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->firstOrFail();

        $blogTranslation = BlogTranslation::where('slug', $slug)
            ->where('language_id', $language->id)
            ->firstOrFail();

        $blog = Blog::with(['user', 'cats','comments.user', 'reactions', 'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }])
            ->findOrFail($blogTranslation->blog_id);

          // Check if the authenticated user has reacted to the blog and get the reaction type
        // $userReactionType = null;
        // if (auth()->check()) {
        //     $userReaction = $blog->reactions()->where('user_id', auth()->id())->first();
        //     $userReactionType = $userReaction ? $userReaction->type : null;
        // }

        // Get recommended blogs
        $recommendedBlogs = Blog::with(['user', 'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }])
            ->withCount(['comments', 'reactions'])
            ->where('id', '!=', $blog->id) // Exclude the current blog
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'blog' => new BlogResource($blog),
            'recommended_blogs' => BlogResource::collection($recommendedBlogs),
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.subtitle' => 'required|string|max:255',
            'translations.*.content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $blog = Blog::findOrFail($id);
        $blog->update([
            'user_id' => $request->user_id,
        ]);

        foreach ($request->translations as $translation) {
            $language = Language::where('code', $translation['language_code'])->first();

            $blogTranslation = BlogTranslation::where('blog_id', $blog->id)
                ->where('language_id', $language->id)
                ->first();

            if ($blogTranslation) {
                $blogTranslation->update([
                    'title' => $translation['title'],
                    'subtitle' => $translation['subtitle'],
                    'content' => $translation['content'],
                ]);
            } else {
                BlogTranslation::create([
                    'blog_id' => $blog->id,
                    'language_id' => $language->id,
                    'title' => $translation['title'],
                    'subtitle' => $translation['subtitle'],
                    'content' => $translation['content'],
                ]);
            }
        }

        return response()->json(new BlogResource($blog->load('translations')));
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();

        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $languageCode = $request->header('Language-Code');
        $language = Language::where('code', $languageCode)->first();

        if (!$language) {
            return response()->json(['message' => 'Language not supported'], 400);
        }

        $blogs = Blog::whereHas('translations', function ($query) use ($language, $search) {
            $query->where('language_id', $language->id)
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('subtitle', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%');
                });
        })
            ->with([
                'translations' => function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                },
                'user',
            ])
            ->paginate(10);

        if ($blogs->isEmpty()) {
            return response()->json(['message' => 'No results found'], 404);
        }

        return BlogResource::collection($blogs);
    }

    public function recommendations(Request $request)
    {
        $languageCode = $request->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->firstOrFail();

        // Assume a simple recommendation based on the most recent blogs in the same language
        $recommendedBlogs = Blog::with(['user', 'translations' => function ($query) use ($language) {
                $query->where('language_id', $language->id);
            }])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return BlogResource::collection($recommendedBlogs);
    }

    public function allSlugs()
    {
        $languages = Language::all();
        $result = [];

        foreach ($languages as $language) {
            $slugs = BlogTranslation::where('language_id', $language->id)
                ->select('slug', 'updated_at')
                ->get()
                ->map(function ($blogTranslation) {
                    return [
                        'slug' => $blogTranslation->slug,
                        'lastMod' => $blogTranslation->updated_at->toIso8601String(),
                    ];
                });

            $result[$language->code] = $slugs;
        }

        return response()->json($result);
    }
}
