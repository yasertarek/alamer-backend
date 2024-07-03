<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured blogs
        $featuredBlogs = Blog::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->where('is_featured', true)
            ->take(5)
            ->get();

        // Fetch recent blogs
        $recentBlogs = Blog::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch popular blogs (example based on likes)
        $popularBlogs = Blog::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->withCount('reactions')
            ->orderBy('reactions_count', 'desc')
            ->take(5)
            ->get();

        // Fetch featured services
        $featuredServices = Service::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->where('is_featured', true)
            ->take(5)
            ->get();

        // Fetch recent services
        $recentServices = Service::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'featuredBlogs' => $featuredBlogs,
            'recentBlogs' => $recentBlogs,
            'popularBlogs' => $popularBlogs,
            'featuredServices' => $featuredServices,
            'recentServices' => $recentServices
        ]);
    }

    private function getLanguageId()
    {
        $languageCode = request()->header('Accept-Language', 'en');
        $language = \App\Models\Language::where('code', $languageCode)->first();
        return $language ? $language->id : 1; // default to 1 if not found
    }
}
