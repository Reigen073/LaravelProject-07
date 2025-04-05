<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;

class FavoriteController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = auth()->user();

    //     // Eager load the advertisement relationship
    //     $favorites = $user->favorites()->with('advertisement')->paginate(6);
    
    //     return view('favorites.index', compact('favorites'));
    // }

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
    public function advertisement()
{
    return $this->belongsTo(Advertisement::class);
}

    
}

