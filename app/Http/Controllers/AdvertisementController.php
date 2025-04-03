<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Bidding;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Middleware\RoleCheck;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $query = Advertisement::query();
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

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
                    $query->latest(); 
                    break;
            }
        } else {
            $query->latest(); 
        }

        $advertisements = $query->paginate(9);

        return view('homepage', compact('advertisements'));

    }

    public function dashboard(Request $request)
    {
        // Start query for advertisements
        $query = Advertisement::where('user_id', auth()->id()); // Only show the user's advertisements
        
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
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
        return view('advertisements.create', compact('advertisements'));
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
            'expires_at' => 'required|date',
        ]);

        if (strtotime($request->expires_at) <= strtotime(now()->toDateString())) {
            return redirect()->back()->with('error', 'De vervaldatum moet na vandaag liggen.');
        }
        $user = auth()->user();
        $advertisementCount = Advertisement::where('user_id', $user->id)
            ->where('type', $request->type)
            ->count();

        if ($advertisementCount >= 4) {
            return redirect()->back()->with('error', 'Je mag maximaal 4 advertenties van elk type hebben.');
        }

        $advertisement = Advertisement::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'category' => $request->category,
            'status' => $request->status,
            'condition' => $request->condition,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            'expires_at' => $request->expires_at,
        ]);
        
        if ($request->has('related_advertisements')) {
            $advertisement->relatedAdvertisements()->attach($request->related_advertisements);
        }

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
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
        return view('advertisements.edit', compact('advertisement', 'advertisements'));
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

        if ($request->has('related_advertisements')) {
            $advertisement->relatedAdvertisements()->sync($request->related_advertisements);
        }

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

    public function buy(Advertisement $advertisement, Advertisement $advertisement2 = null)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt je eigen advertentie niet kopen.');
        }

        $advertisement->status = 'sold';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        if ($advertisement2 !== null) {
            $advertisement2->status = 'sold';
            $advertisement2->acquirer_user_id = auth()->id();
            $advertisement2->save();
        }
        return redirect()->route('homepage')->with('success', 'Advertentie gekocht!');
    }

    public function rent(Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt je eigen advertentie niet huren.');
        }

        $advertisement->status = 'rented';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        return redirect()->route('homepage')->with('success', 'Advertentie gehuurd!');
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
    
        return redirect()->route('homepage')->with('success', 'Bod succesvol geplaatst!');
    }

    public function biddingAccept(Request $request, $id){
        $bidding = Bidding::findOrFail($id);
        $advertisement = Advertisement::findOrFail($bidding->advertisement_id);

        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Je kunt dit bod niet accepteren.');
        }

        $bidding->status = 'accepted';
        $bidding->save();

        $advertisement->status = 'sold';
        $advertisement->acquirer_user_id = $bidding->user_id;
        $advertisement->save();

        return redirect()->route('advertisements.agenda')->with('success', 'Bod geaccepteerd!');
    }

    public function biddingReject(Request $request, $id){
        $bidding = Bidding::findOrFail($id);
        $advertisement = Advertisement::findOrFail($bidding->advertisement_id);

        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('advertisments.agenda')->with('error', 'Je kunt dit bod niet afwijzen.');
        }

        $bidding->status = 'rejected';
        $bidding->save();

        return redirect()->route('advertisements.agenda')->with('success', 'Bod afgewezen!');
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
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
    
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
    
        $csvData = array_map('str_getcsv', file($path));
        $header = array_shift($csvData);
    
        $requiredColumns = ['title', 'description', 'price', 'category', 'type', 'status', 'condition', 'expires_at'];
    
        // Check if all required columns exist in the CSV
        if (array_diff($requiredColumns, $header)) {
            return redirect()->back()->with('error', 'CSV-bestand heeft een onjuist formaat.');
        }
    
        $user = auth()->user();
        $advertisementCounts = Advertisement::where('user_id', $user->id)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    
        $skippedAds = 0;
        $addedAds = 0;
    
        foreach ($csvData as $row) {
            if (count($row) !== count($header)) {
                return redirect()->back()->with('error', 'CSV-bestand heeft een onjuiste hoeveelheid gegevens in een van de rijen.');
            }
    
            $row = array_combine($header, $row);
            $type = $row['type'];
    
            // **Check current total count for this type before adding**
            if (($advertisementCounts[$type] ?? 0) >= 4) {
                $skippedAds++;
                continue; // Skip this advertisement if the limit is reached
            }
    
            // **Insert the new advertisement**
            Advertisement::create([
                'user_id' => $user->id,
                'title' => $row['title'],
                'description' => $row['description'],
                'price' => (float) $row['price'],
                'category' => $row['category'],
                'type' => $type,
                'status' => $row['status'],
                'condition' => $row['condition'],
                'expires_at' => $row['expires_at'],
            ]);
    
            // **Increment the count for this type**
            $advertisementCounts[$type] = ($advertisementCounts[$type] ?? 0) + 1;
            $addedAds++;
        }
    
        if ($addedAds == 0) {
            return redirect()->route('dashboard')->with('error', 'Geen advertenties toegevoegd. Limiet al bereikt.');
        }
    
        $message = "$addedAds advertenties succesvol geüpload!";
        if ($skippedAds > 0) {
            $message .= " $skippedAds advertenties zijn niet toegevoegd omdat de limiet per type is bereikt.";
        }
    
        return redirect()->route('dashboard')->with('success', $message);
    }
    

    
    public function showUploadForm()
    {
        return view('advertisements.upload');
    }
}
