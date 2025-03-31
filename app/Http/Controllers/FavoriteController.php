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
            return back()->with('success', 'Advertentie verwijderd uit favorieten.');
        } else {
            $user->favorites()->attach($id);
            return back()->with('success', 'Advertentie toegevoegd aan favorieten.');
        }
    }
}

