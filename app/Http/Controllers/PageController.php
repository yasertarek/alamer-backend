<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Resources\ServiceResource;
use App\Http\Resources\SecUserResource;
use App\Models\Language;
use App\Models\BlogTranslation;

use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\Blog;
use App\Models\User;
use App\Models\Visit;

class PageController extends Controller
{
    private function getLanguageId()
    {
        $languageCode = request()->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->first();
        return $language ? $language->id : 1; // default to 1 if not found
    }
    /**
     * Display a page by its slug.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();


        $finalRes = [
            'slug'   => $page->slug,
            'title'  => $page->title,
            'content' => $page->content,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,

            // OG metadata returned as a nested object
            'og' => [
                'title'       => $page->og_title ?? $page->title,
                'description' => $page->og_description
                    ?? Str::limit(strip_tags($page->content), 160),
                'image'       => $page->og_image,
                'type'        => $page->og_type,
                'locale'      => $page->og_locale,
            ],

            'updated_at' => $page->updated_at
        ];


        if (strtolower($slug) === 'about') {
            $totalBlogs = Blog::count();
            $totalClients = User::where('role', 'user')->count();
            $totalVisits = Visit::count();
            $finalRes['metrics'] = [
                'total_blogs' => $totalBlogs,
                'total_clients' => $totalClients,
                'total_visits' => $totalVisits,
            ];
        }

        if (strtolower($slug) === 'services') {
            $arLangId = Language::where('code', 'ar')->first()->id;

            $totalClients = User::where('role', 'user')->get();

            $services = Service::with([
                'translations' => function ($query) use ($arLangId) {
                    $query->where('language_id', $arLangId);
                },
                'user',
                'rates'
            ])->select('services.*')
                ->orderBy('created_at', 'desc')->paginate(10);


            $finalRes['services'] = ServiceResource::collection($services);


            $finalRes['clients'] = SecUserResource::collection($totalClients);
        }

        return response()->json($finalRes);
    }


    /**
     * Update a page and its OG metadata.
     */
    public function update(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // validation rules
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',

            // Open Graph metadata validation
            'og_title'        => 'nullable|string|max:255',
            'og_description'  => 'nullable|string|max:500',
            'og_image'        => 'nullable|string|max:500',
            'og_type'         => 'nullable|string|max:50',
            'og_locale'       => 'nullable|string|max:10',
        ]);

        // update page
        $page->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully.',
            'page'    => [
                'slug'   => $page->slug,
                'title'  => $page->title,
                'content' => $page->content,

                'og' => [
                    'title'       => $page->og_title ?? $page->title,
                    'description' => $page->og_description
                        ?? Str::limit(strip_tags($page->content), 160),
                    'image'       => $page->og_image,
                    'type'        => $page->og_type,
                    'locale'      => $page->og_locale,
                ],

                'updated_at' => $page->updated_at
            ]
        ]);
    }


    /**
     * List all pages (for admin dashboard table).
     */
    public function index()
    {
        $pages = Page::select('id', 'slug', 'title', 'updated_at')->get();

        return response()->json($pages);
    }

    public function sitemap()
    {
        $frontendUrl = config('app.frontend_url'); // set FRONTEND_URL in config/app.php

        // Get Arabic language id safely
        $arLang = Language::where('code', 'ar')->first();
        if (!$arLang) {
            return response()->json(['error' => 'Arabic language not found'], 500);
        }

        // Pages
        $pages = Page::select('slug', 'updated_at')->get()->map(function ($p) use ($frontendUrl) {
            return [
                'loc' => $frontendUrl . '/' . $p->slug,
                'lastMod' => $p->updated_at->toIso8601String(),
            ];
        });

        // Blog posts (Arabic)
        $slugs = BlogTranslation::where('language_id', $arLang->id)
            ->select('slug', 'updated_at')
            ->get()->map(function ($b) use ($frontendUrl) {
                return [
                    'loc' => $frontendUrl . '/blog/' . $b->slug,
                    'lastMod' => $b->updated_at->toIso8601String(),
                ];
            });

        // Services (Arabic)
        $serSlugs = ServiceTranslation::where('language_id', $arLang->id)
            ->select('slug', 'updated_at')
            ->get()->map(function ($s) use ($frontendUrl) {
                return [
                    'loc' => $frontendUrl . '/services/' . $s->slug,
                    'lastMod' => $s->updated_at->toIso8601String(),
                ];
            });

        // Merge collections correctly
        $result = $pages
            ->concat($slugs)
            ->concat($serSlugs)
            ->values(); // clean numeric keys

        return response()->json($result);
    }



    /**
     * Create a new static page (admin use).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'            => 'required|string|unique:pages,slug',
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',

            'og_title'        => 'nullable|string|max:255',
            'og_description'  => 'nullable|string|max:500',
            'og_image'        => 'nullable|string|max:500',
            'og_type'         => 'nullable|string|max:50',
            'og_locale'       => 'nullable|string|max:10',
        ]);

        $page = Page::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully.',
            'page' => $page
        ], 201);
    }


    /**
     * Delete a static page.
     */
    public function destroy(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully.'
        ]);
    }


    public function prerenderRoutes()
    {
        $pages = Page::select('slug')->get()->map(function ($pageItem) {
            return '/' . $pageItem->slug;
        });

        $arLangId = Language::where('code', 'ar')->first()->id;
        $blogSlugs = BlogTranslation::where('language_id', $arLangId)
            ->select('slug')
            ->get()->map(function ($blogTranslation) {
                return '/blog/' . $blogTranslation->slug;
            });
        $servicesSlugs = ServiceTranslation::where('language_id', $arLangId)
            ->select('slug')
            ->get()->map(function ($serviceTranslation) {
                return '/services/' . $serviceTranslation->slug;
            });

        return response()->json(array_merge(
            $pages->toArray(),
            $blogSlugs->toArray(),
            $servicesSlugs->toArray()
        ));
    }
}
