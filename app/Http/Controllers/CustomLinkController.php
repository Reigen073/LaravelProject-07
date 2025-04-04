<?php

namespace App\Http\Controllers;

use App\Models\CustomLink;
use Illuminate\Http\Request;

class CustomLinkController extends Controller
{
    // Method to handle the form submission
    public function store(Request $request)
    {
        // Validate the link name
        $request->validate([
            'link_name' => 'required|string|unique:custom_links', // Ensure it's unique
        ]);

        // Create a new CustomLink entry in the database
        $customLink = new CustomLink();
        $customLink->link_name = $request->link_name;
        $customLink->save();

        // Redirect back with the link name stored in session
        return redirect()->back()->with('link_name', $customLink->link_name);
    }
}
