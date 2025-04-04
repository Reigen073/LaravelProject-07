<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSettingsController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'show_intro' => 'required|boolean',
            'show_ads' => 'required|boolean',
            'show_favorites' => 'required|boolean',
            'show_image' => 'required|boolean',
            'custom_link' => 'required|boolean',
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
        ]);
        // Store the user's settings for intro text visibility
        $user->dashboard_settings = array_merge(
            $user->dashboard_settings ?? [],
            [
                'show_intro' => $validated['show_intro'],
                'show_ads' => $validated['show_ads'],
                'show_favorites' => $validated['show_favorites'],
                'show_image' => $validated['show_image'], // Store image section visibility
                'bg_color' => $validated['bg_color'],
                'text_color' => $validated['text_color'],
                'custom_link' => $validated('custom_link') 

            ]
        );
        $user->save();

        return response()->json(['success' => true]);
    }

    public function fetch()
    {
        return response()->json(Auth::user()->dashboard_settings ?? []);
    }
    
}
