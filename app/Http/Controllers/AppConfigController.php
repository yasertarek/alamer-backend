<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Resources\NavbarResource;
use App\Models\Language;
use App\Models\Cats;
use App\Models\Navbar;
use App\Models\Phone;

class AppConfigController extends Controller
{
    //
    public function getConfig()
    {
        $languages = Language::all();

        $cats = Cats::paginate(10);

        $phones = Phone::all();

        $user = null;
        if (Auth::check()) {
            // The user is logged in
            $user = Auth::user();
        } else {
            // The user is not logged in
        }

        $navbarType = 'guest';
        if ($user) {
            if ($user->role === 'user') {
                $navbarType = 'user';
            } else {
                $navbarType = 'admin';
            }
        }
        // $navbarType = $request->input('navbar_type', 'Wguest');
        $navbar = Navbar::with([
            'translations' => function ($query) {
                $query->where('language_id', $this->getLanguageId());
            }
        ])->where('group', 'like', $navbarType)
            ->orderBy('order')
            ->get();



        $config = [
            // 'app_name' => config('app.name'),
            // 'app_version' => config('app.version'),
            // 'support_email' => config('app.support_email'),
            // Add other configuration settings as needed
            'navbar' => NavbarResource::collection($navbar),
            "languages" => $languages,
            "cats" => $cats,
            "phones" => $phones,
        ];

        return response()->json($config);
    }
        private function getLanguageId()
    {
        $languageCode = request()->header('Language-Code', 'ar');
        $language = Language::where('code', $languageCode)->first();
        return $language ? $language->id : 1; // default to 1 if not found
    }
}
