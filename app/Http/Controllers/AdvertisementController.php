<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Bidding;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        // Start query for advertisements
        $query = Advertisement::query();
        // Apply title filter if a search term is provided
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        // Apply category filter if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Apply condition filter if provided
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // Apply status filter if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Apply sorting if provided
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                default:
                    $query->latest();  // Default sorting: latest first
                    break;
            }
        } else {
            $query->latest();  // Default sorting: latest first
        }

        // Paginate results
        $advertisements = $query->paginate(9);

        return view('homepage', compact('advertisements'));
    }

    public function dashboard(Request $request)
    {
        // Start query for advertisements
        $query = Advertisement::query();
    
        // Apply title filter if a search term is provided
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
    
        // Paginate results with 6 items per page
        $advertisements = $query->paginate(6);
    
        return view('dashboard', compact('advertisements'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'required|date|after:today',
            'acquirer_user_id' => 'nullable|exists:users,id',
        ]);
    
        $advertisement = Advertisement::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'category' => $request->category,
            'status' => $request->status,
            'condition' => $request->condition,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            'expires_at' => $request->expires_at,
            'acquirer_user_id' => $request->acquirer_user_id,
        ]);

        $advertisement->qr_code = $this->generateQrCode($advertisement);
        $advertisement->save();

        return redirect('dashboard')->with('success', 'Advertentie geplaatst!');
    }

    public function generateQrCode($advertisement)
    {
        $url = url('/advertisements/' . $advertisement->id);
        return QrCode::size(100)->generate($url);
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

    public function history(){
        $user = auth()->user();
        $advertisements = Advertisement::where('acquirer_user_id', $user->id)
            ->where('status', 'sold')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('advertisements.history', compact('advertisements'));
    }
    public function favorites()
    {
        $favorites = auth()->user()->favorites()->latest()->paginate(9);
        return view('advertisements.favorites', compact('favorites'));
    }

}
