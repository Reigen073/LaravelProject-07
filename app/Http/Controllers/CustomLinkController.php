<?php

namespace App\Http\Controllers;

use App\Models\CustomLink;
use Illuminate\Http\Request;

class CustomLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'link_name' => 'required|string|max:255|unique:custom_links',
        ]);

        $customLink = new CustomLink();
        $customLink->link_name = $request->link_name;
        $customLink->save();

        return redirect()->back()->with('link_name', $customLink->link_name);
    }
    public function handleLinkName($link_name)
    {
        $customLink = CustomLink::where('link_name', $link_name)->first();

        if (!$customLink) {
            abort(404, __('messages.page_not_found'));
        }

        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
