<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'type' => 'required|in:advertisement,advertiser',
        ]);

        if ($request->type === 'advertisement') {
            $advertisement_id = Advertisement::findOrFail($id)->id; // Zorgt ervoor dat je alleen de ID opslaat
            $advertiser_id = null; // Zorg dat er geen dubbele ID's worden opgeslagen
        } else {
            $advertisement_id = null; // Zorg ervoor dat 'advertisement_id' expliciet NULL wordt als het niet gebruikt wordt
            $advertiser_id = $id;
        }
        
        Review::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $advertisement_id,
            'advertiser_id' => $advertiser_id,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return redirect()->route('advertisements.info', ['id' => $advertisement_id])->with('success', 'Review geplaatst!');
    }
}
