<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Blog;
use App\Models\Service;
use App\Models\Language;
use App\Models\LandingPageSection;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Resources\ServiceResource;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $landingPageSections = LandingPageSection::orderBy('order')->get()->mapWithKeys(fn($section) => [
            $section->key => $section->data
        ]);;

        // Fetch featured blogs
        $featuredBlogs = Blog::with([
            'user',
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->where('is_featured', true)
            ->take(5)
            ->get();

        // Fetch recent blogs
        $recentBlogs = Blog::with([
            'user' => function ($query) {
                $query->select('id', 'name'); // Only load these columns
            },
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch popular blogs (example based on likes)
        $popularBlogs = Blog::with([
            'user',
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
            },
            'ordersCount'
        ])
            ->where('is_featured', true)
            // ->take(5)
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
            'featuredBlogs' => BlogResource::collection($featuredBlogs),
            'recentBlogs' => BlogResource::collection($recentBlogs),
            'popularBlogs' => BlogResource::collection($popularBlogs),
            'featuredServices' => ServiceResource::collection($featuredServices),
            'recentServices' => ServiceResource::collection($recentServices),
            "sections" => $landingPageSections,
        ]);
    }

    private function getLanguageId()
    {
        $languageCode = request()->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->first();
        return $language ? $language->id : 1; // default to 1 if not found
    }
}
