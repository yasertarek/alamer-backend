<?php

namespace App\Http\Controllers;

use App\Models\LandingPageSection;
use App\Services\LandingPageValidationService;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    // Get all landing sections (for Nuxt frontend)
    public function index()
    {
        return LandingPageSection::orderBy('order')->get();
    }

    // Get a single section
    public function show($key)
    {
        return LandingPageSection::where('key', $key)->firstOrFail();
    }

    // Update section securely
    public function update(Request $request, $key)
    {
        $section = LandingPageSection::where('key', $key)->firstOrFail();

        $rules = LandingPageValidationService::rulesFor($key);

        $validated = $request->validate($rules);

        $section->update(['data' => $validated]);

        return [
            'success' => true,
            'message' => 'Section updated successfully.',
            'section' => $section
        ];
    }
}
