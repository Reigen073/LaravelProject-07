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
            'show_intro_text' => 'required|boolean',
            'show_ads' => 'required|boolean',
            'show_favorites' => 'required|boolean',
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
        ]);
        // Store the user's settings for intro text visibility
        $user->dashboard_settings = array_merge(
            $user->dashboard_settings ?? [],
            ['show_intro_text' => $validated['show_intro_text']]
        );
        $user->save();

        return response()->json(['success' => true]);
    }

    public function fetch()
    {
        return response()->json(Auth::user()->dashboard_settings ?? []);
    }
    
}
