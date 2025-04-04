<?php

// app/Http/Controllers/DashboardSettingsController.php
namespace App\Http\Controllers;

use App\Models\DashboardSetting;
use Illuminate\Http\Request;

class DashboardSettingsController extends Controller
{
    public function store(Request $request)
    {
        // Validate the settings
        $validated = $request->validate([
            'show_ads' => 'boolean',
            'show_favorites' => 'boolean',
            'show_intro' => 'boolean',
            'show_image' => 'boolean',
            'show_custom_link' => 'boolean',
            'show_contracts' => 'boolean',
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
        ]);

        // Save the settings for the logged-in user
        $userSettings = DashboardSetting::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return response()->json($userSettings);
    }

    public function fetch()
    {
        $settings = DashboardSetting::where('user_id', auth()->id())->first();

        // If no settings are found, return default settings
        if (!$settings) {
            $settings = DashboardSetting::create([
                'user_id' => auth()->id(),
                'show_ads' => true,
                'show_favorites' => true,
                'show_intro' => true,
                'show_image' => true,
                'show_custom_link' => true,
                'show_contracts' => true,
                'bg_color' => '#ffffff',
                'text_color' => '#000000',
            ]);
        }

        return response()->json($settings);
    }
}

