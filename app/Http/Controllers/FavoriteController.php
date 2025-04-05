<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;

class FavoriteController extends Controller
{
    public function toggleFavorite($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        $user = auth()->user();

        if ($user->favorites()->where('advertisement_id', $id)->exists()) {
            $user->favorites()->detach($id);
            return back()->with('success', __('messages.removed_from_favorites'));
        } else {
            $user->favorites()->attach($id);
            return back()->with('success', __('messages.added_to_favorites'));
        }
    }
    public function advertisement()
{
    return $this->belongsTo(Advertisement::class);
}

    
}

