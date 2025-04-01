<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Bidding;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::latest()->take(10)->get();
        return view('homepage', compact('advertisements'));
    }

    public function create()
    {
        return view('advertisements.create');
    }

    public function info($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        return view('advertisements.info', compact('advertisement'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'type' => 'required|in:buy,rent,bidding',
            'status' => 'required|in:available,rented,sold',
            'condition' => 'required|in:new,used,refurbished',
            'qr_code' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'required|date|after:today',
            'acquirer_user_id' => 'nullable|exists:users,id',
        ]);
    
        Advertisement::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'category' => $request->category,
            'status' => $request->status,
            'condition' => $request->condition,
            'qr_code' => $request->qr_code,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            'expires_at' => $request->expires_at,
            'acquirer_user_id' => $request->acquirer_user_id,
        ]);

        return redirect('dashboard')->with('success', 'Advertentie geplaatst!');
    }
    public function userAdvertisements()
    {
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('advertisements'));
    }

    public function edit(Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je mag deze advertentie niet bewerken.');
        }
        return view('advertisements.edit', compact('advertisement'));
    }
    public function update(Request $request, Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je mag deze advertentie niet bewerken.');
            }

        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'type' => 'required|in:buy,rent,bidding',
            'status' => 'required|in:available,rented,sold',
            'condition' => 'required|in:new,used,refurbished',
            'qr_code' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'required|date|after:today',
            'acquirer_user_id' => 'nullable|exists:users,id',
        ]);

        $advertisement->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'type' => $request->type,
            'status' => $request->status,
            'condition' => $request->condition,
            'qr_code' => $request->qr_code,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : $advertisement->image,
            'expires_at' => $request->expires_at,
            'acquirer_user_id' => $request->acquirer_user_id,
        ]);

        return redirect()->route('dashboard', $advertisement->id)
            ->with('success', 'Advertentie bijgewerkt!');
    }
    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je mag deze advertentie niet verwijderen.');
        }

        $advertisement->delete();

        return redirect()->route('dashboard')->with('success', 'Advertentie succesvol verwijderd.');
    }

    public function agenda(){
        $user = auth()->user();

        if ($user->role === 'gebruiker') {
            $advertisements = Advertisement::where('acquirer_user_id', $user->id)
                ->where('status', 'rented')
                ->whereNotNull('expires_at')
                ->orderBy('expires_at', 'asc')
                ->get();

            return view('advertisements.agenda', compact('advertisements'));
        } else {
            $expiringAdvertisements = Advertisement::where('user_id', $user->id)
                ->whereNotNull('expires_at')
                ->where('expires_at', '>=', now())
                ->orderBy('expires_at', 'asc')
                ->get();

            $rentedAdvertisements = Advertisement::where('user_id', $user->id)
                ->where('status', 'rented')
                ->whereNotNull('expires_at')
                ->orderBy('expires_at', 'asc')
                ->get();
            
            $biddings = Bidding::with(['advertisement', 'user'])->get();


            return view('advertisements.agenda', compact('expiringAdvertisements', 'rentedAdvertisements', 'biddings'));
        }
    }

    public function buy(Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt je eigen advertentie niet kopen.');
        }

        $advertisement->status = 'sold';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        return redirect()->route('home')->with('success', 'Advertentie gekocht!');
    }

    public function rent(Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt je eigen advertentie niet huren.');
        }

        $advertisement->status = 'rented';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        return redirect()->route('home')->with('success', 'Advertentie gehuurd!');
    }

    public function bidding(Request $request, Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt niet op je eigen advertentie bieden.');
        }
    
        $request->validate([
            'bid_amount' => 'required|numeric|min:0.01',
        ]);
    
        Bidding::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $advertisement->id,
            'bid_amount' => $request->bid_amount,
        ]);
    
        return redirect()->route('home')->with('success', 'Bod succesvol geplaatst!');
    }
    


}
