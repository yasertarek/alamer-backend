<?php

namespace App\Services;

class LandingPageValidationService
{
    public static function rulesFor(string $key): array
    {
        return match ($key) {
            'header' => [
                'backgroundImage' => 'required|string',
                'heading' => 'required|string|max:255',
                'phoneNumber' => 'required|string|max:20|exists:phones,number',
                'phoneText' => 'required|string|max:255',
            ],

            'cta' => [
                'title' => 'required|string|max:255',
                'phoneNumber' => 'required|string|max:20|exists:phones,number',
                'phoneText' => 'required|string|max:255',
                'whatsappNumber' => 'required|string|max:20|exists:phones,number',
                'whatsappText' => 'required|string|max:255',
            ],

            'about_article_1' => [
                'subtitle' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'link' => 'nullable|string',
            ],

            'about_article_2' => [
                'image' => 'required|string',
                'subtitle' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'buttonText' => 'required|string|max:255',
            ],

            default => throw new \Exception("Invalid section key: $key"),
        };
    }
}
