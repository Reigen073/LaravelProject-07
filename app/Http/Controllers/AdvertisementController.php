<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\Bidding;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Middleware\RoleCheck;
use App\Models\Contract;

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
        $query = Advertisement::where('user_id', auth()->id()); 
        
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $advertisements = $query->paginate(6);

        $favorites = auth()->user()->favorites()->pluck('advertisement_id');
        $favoriteAdvertisementsQuery = Advertisement::whereIn('id', $favorites);
        if ($request->has('favorite_search') && $request->favorite_search) {
            $favoriteAdvertisementsQuery->where('title', 'like', '%' . $request->favorite_search . '%');
        }
         $favoriteAdvertisements = $favoriteAdvertisementsQuery->latest()->paginate(6);
         $contractsQuery = Contract::where('user_id', auth()->id());

         if ($request->has('contract_search') && $request->contract_search) {
            $contractsQuery->where('contract_name', 'like', '%' . $request->contract_search . '%');
        }
        $contracts = $contractsQuery->paginate(3); 

        
        return view('dashboard', compact('advertisements', 'favoriteAdvertisements', 'contracts'));
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
            'wear_rate' => 'required|numeric|min:0|max:1',
            'type' => 'required|in:buy,rent,bidding',
            'status' => 'required|in:available,rented,sold',
            'condition' => 'required|in:new,used,refurbished',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expires_at' => 'required|date',
        ]);

        if (strtotime($request->expires_at) <= strtotime(now()->toDateString())) {
            return redirect()->back()->with('error', __('messages.date_must_be_after_today'));
        }
        $user = auth()->user();
        $advertisementCount = Advertisement::where('user_id', $user->id)
            ->where('type', $request->type)
            ->count();

        if ($advertisementCount >= 4) {
            return redirect()->back()->with('error', __('messages.max_of_4_adverts'));
        }

        $advertisement = Advertisement::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'wear_rate' => $request->wear_rate,
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

        return redirect('dashboard')->with('success', __('messages.placed_advert'));
    }


    public function generateQrCode($advertisement)
    {
        $url = url('/advertisements/' . $advertisement->id);
        return QrCode::size(100)->generate($url);
    }

    public function userAdvertisements()
    {
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->paginate(6);
        return view('dashboard', compact('advertisements'));
    }

    public function edit(Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', __('messages.cant_edit_advert'));
        }
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
        return view('advertisements.edit', compact('advertisement', 'advertisements'));
    }
    public function update(Request $request, Advertisement $advertisement)
    {
        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', __('messages.cant_edit_advert'));
            }

        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'type' => 'required|in:buy,rent,bidding',
            'wear_rate' => 'required|numeric|min:0|max:1',
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
            'wear_rate' => $request->wear_rate,
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
        $advertisement->delete();
        return redirect()->route('dashboard')->with('success', __('messages.advert_deleted_succesfully'));
    }

    protected function applySorting($query, $sort)
    {
        switch ($sort) {
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
    }

    public function agenda(Request $request){
        $user = auth()->user();

        $myRentedQuery = Advertisement::query();
        $expiringQuery = Advertisement::query();
        $rentedOutQuery = Advertisement::query();
        $biddingsQuery = Bidding::query();

        if ($request->filter_set === 'expiring') {
            if ($request->has('search') && $request->search) {
                $expiringQuery->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category') && $request->category) {
                $expiringQuery->where('category', $request->category);
            }

            if ($request->has('condition') && $request->condition) {
                $expiringQuery->where('condition', $request->condition);
            }

            if ($request->has('status') && $request->status) {
                $expiringQuery->where('status', $request->status);
            }

            if ($request->filled('sort')) {
                $this->applySorting($expiringQuery, $request->sort);
            }
        }

        if ($request->filter_set === 'rentedout') {
            if ($request->has('search') && $request->search) {
                $rentedOutQuery->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category') && $request->category) {
                $rentedOutQuery->where('category', $request->category);
            }

            if ($request->has('condition') && $request->condition) {
                $rentedOutQuery->where('condition', $request->condition);
            }

            if ($request->has('status') && $request->status) {
                $rentedOutQuery->where('status', $request->status);
            }

            if ($request->filled('sort')) {
                $this->applySorting($rentedOutQuery, $request->sort);
            }
        }

        if ($request->filter_set === 'myrented') {
            if ($request->has('search') && $request->search) {
                $myRentedQuery->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category') && $request->category) {
                $myRentedQuery->where('category', $request->category);
            }

            if ($request->has('condition') && $request->condition) {
                $myRentedQuery->where('condition', $request->condition);
            }

            if ($request->has('status') && $request->status) {
                $myRentedQuery->where('status', $request->status);
            }

            if ($request->filled('sort')) {
                $this->applySorting($myRentedQuery, $request->sort);
            }
        }

        if ($request->filter_set === 'bidding') {
            if ($request->has('search') && $request->search) {
                $biddingsQuery->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('sort')) {
                $this->applySorting($biddingsQuery, $request->sort);
            }
        }

        if ($user->role === 'gebruiker') {
            $advertisements = Advertisement::where('acquirer_user_id', $user->id)
            ->where('status', 'rented')
            ->whereNotNull('expires_at')
            ->orderBy('expires_at', 'asc')
            ->paginate(6);

            return view('advertisements.agenda', compact('advertisements'));
        } else {
            $expiringAdvertisements = $expiringQuery
            ->where('user_id', $user->id)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>=', now())
            ->orderBy('expires_at', 'asc')
            ->paginate(6);

            $rentedAdvertisements = $rentedOutQuery
                ->where('user_id', $user->id)
                ->where('status', 'rented')
                ->whereNotNull('expires_at')
                ->orderBy('expires_at', 'asc')
                ->paginate(6);

            $biddings = $biddingsQuery
                ->where('user_id', $user->id)
                ->paginate(6);

            
            $biddings = Bidding::with(['advertisement', 'user'])->paginate(6);


            return view('advertisements.agenda', compact('expiringAdvertisements', 'rentedAdvertisements', 'biddings'));
        }
    }

    public function buy(Advertisement $advertisement, Advertisement $advertisement2 = null)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('error', __('messages.cant_buy_own_advert'));
        }

        $advertisement->status = 'sold';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        if ($advertisement2 !== null) {
            $advertisement2->status = 'sold';
            $advertisement2->acquirer_user_id = auth()->id();
            $advertisement2->save();
        }
        return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('success', __('messages.bought_advert'));
    }

    public function rent(Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('error', __('messages.cant_rent_own_advert'));
        }

        $advertisement->status = 'rented';
        $advertisement->acquirer_user_id = auth()->id();
        $advertisement->save();

        return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('success', __('messages.rented_advert'));
    }

    public function bidding(Request $request, Advertisement $advertisement)
    {
        if ($advertisement->user_id === auth()->id()) {
            return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('error', __('messages.cant_bid_own_advert'));
        }
        
        $request->validate([
            'bid_amount' => 'required|numeric|min:0.01',
        ]);
    
        Bidding::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $advertisement->id,
            'bid_amount' => $request->bid_amount,
        ]);
    
        return redirect()->route('advertisements.info', ['id' => $advertisement->id])->with('success', __('messages.bid_placed'));
    }

    public function biddingAccept(Request $request, $id){
        $bidding = Bidding::findOrFail($id);
        $advertisement = Advertisement::findOrFail($bidding->advertisement_id);

        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('advertisments.agenda')->with('error', __('messages.cant_accept_bid'));
        }

        $bidding->status = 'accepted';
        $bidding->save();

        $advertisement->status = 'sold';
        $advertisement->acquirer_user_id = $bidding->user_id;
        $advertisement->save();

        return redirect()->route('advertisements.agenda')->with('success', __('messages.bid_accepted'));
    }

    public function biddingReject(Request $request, $id){
        $bidding = Bidding::findOrFail($id);
        $advertisement = Advertisement::findOrFail($bidding->advertisement_id);

        if ($advertisement->user_id !== auth()->id()) {
            return redirect()->route('advertisments.agenda')->with('error', __('messages.cant_reject_bid'));
        }

        $bidding->status = 'rejected';
        $bidding->save();

        return redirect()->route('advertisements.agenda')->with('success', __('messages.bid_rejected'));
    }

    public function history(Request $request){
        $user = auth()->user();
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
        if ($request->filled('sort')) {
            $this->applySorting($query, $request->sort);
        } else {
            $query->latest(); 
        }

        $advertisements = $query
            ->where('acquirer_user_id', $user->id)
            ->where('status', 'sold')
            ->whereNotNull('expires_at')
            ->orderBy('expires_at', 'asc')
            ->paginate(6);
            
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

        if (array_diff($requiredColumns, $header)) {
            return redirect()->back()->with('error', __('messages.csv_wrong_format'));
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
                return redirect()->back()->with('error', __('messages.csv_incorrect_amountdata'));
            }
    
            $row = array_combine($header, $row);
            $type = $row['type'];
    
            if (($advertisementCounts[$type] ?? 0) >= 4) {
                $skippedAds++;
                continue;
            }
    
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
            $advertisementCounts[$type] = ($advertisementCounts[$type] ?? 0) + 1;
            $addedAds++;
        }
    
        if ($addedAds == 0) {
            return redirect()->route('dashboard')->with('error', __('messages.csv_no_adverts_added'));
        }
    
        $message = __('messages.upload_success', ['count' => $addedAds]);

        if ($skippedAds > 0) {
            $message .= ' ' . __('messages.upload_skipped', ['count' => $skippedAds]);
        }
    
        return redirect()->route('dashboard')->with('success', $message);
    }
    

    
    public function showUploadForm()
    {
        return view('advertisements.upload');
    }
}
