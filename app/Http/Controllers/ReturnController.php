<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\ReturnRequest;
use App\Http\Controllers\AdvertisementController;

class ReturnController extends Controller
{
    public function index() {
        $advertisements = Advertisement::where('user_id', auth()->id())->pluck('id');
        $returns = ReturnRequest::whereIn('advertisement_id', $advertisements)->latest()->paginate(6);
        return view('returns.index', compact('returns'));
    }
    
    public function approve($id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);
        $advertisement = Advertisement::findOrFail($returnRequest->advertisement_id);
        $returnRequest->update(['status' => 'approved']);
        $advertisement->update([
            'status' => 'available',
            'acquirer_user_id' => null,
        ]);
        return back()->with('success', 'Retourverzoek goedgekeurd! Advertentie is nu weer beschikbaar.');
    }

    public function reject($id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->update(['status' => 'rejected']);
        return back()->with('error', 'Retourverzoek afgekeurd!');
    }

    public function store(Request $request, $id) {
        $request->validate([
            'reason' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->acquirer_user_id !== auth()->id()) {
            return back()->with('error', 'Je kunt alleen producten retourneren die je hebt gekocht.');
        }

        if ($advertisement->type === 'rent') {
            $wearRate = $advertisement->wear_rate ?? 0;
            $rentalDays = now()->diffInDays($advertisement->rental_start_date);
            $depreciation = $advertisement->price * ($wearRate * $rentalDays);
            $newPrice = max(0, $advertisement->price - $depreciation);
            $advertisement->update([
                'price' => $newPrice,
                'status' => 'available',
                'acquirer_user_id' => null
            ]);

            ReturnRequest::create([
                'advertisement_id' => $advertisement->id,
                'user_id' => auth()->id(),
                'image' => $request->imagePath,
                'reason' => $request->reason,
                'status' => 'approved',
            ]);

            return back()->with('success', 'Het product is teruggebracht en weer beschikbaar voor verhuur.');
        }

        $imagePath = $request->file('image') ? $request->file('image')->store('returns', 'public') : null;

        ReturnRequest::create([
            'advertisement_id' => $advertisement->id,
            'user_id' => auth()->id(),
            'image' => $imagePath,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Retourverzoek ingediend!');
    }
}
