<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;

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
            'status' => 'required|in:available,rented,sold',
            'qr_code' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'condition' => 'required|in:new,used,refurbished',
        ]);

        Advertisement::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'status' => $request->status,
            'qr_code' => $request->qr_code,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            'condition' => $request->condition,
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
            'status' => 'required|in:available,rented,sold',
            'condition' => 'required|in:new,used,refurbished',
        ]);

        $advertisement->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'status' => $request->status,
            'condition' => $request->condition,
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


}
