<?php

namespace App\Http\Controllers;

use App\Models\Navbar;
use Illuminate\Http\Request;
use App\Http\Resources\NavbarResource;
use App\Models\Language;

class WebsiteSettings extends Controller
{
    public function index(Request $request)
    {
        $guestNavbar = Navbar::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])->where('group', 'like', 'guest')
        ->get();
        
        $userNavbar = Navbar::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])->where('group', 'like', 'user')
        ->get();
        $adminNavbar = Navbar::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])->where('group', 'like', 'admin')
        ->get();

        return response()->json([
            "navbar" => [
                "guest" => NavbarResource::collection($guestNavbar),
                "user" => NavbarResource::collection($userNavbar),
                "admin" => NavbarResource::collection($adminNavbar),
            ]
        ]);
    }

    private function getLanguageId()
    {
        $languageCode = request()->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->first();
        return $language ? $language->id : 1; // default to 1 if not found
    }
}
