<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\ReturnRequest;
use App\Http\Controllers\AdvertisementController;

class ReturnController extends Controller
{
    public function index(Request $request) {
        $user = auth()->user();

        $advertisementIds = Advertisement::where('user_id', $user->id)->pluck('id');
    
        $returns = ReturnRequest::with(['user', 'advertisement'])
            ->whereIn('advertisement_id', $advertisementIds)
            ->when($request->buy_status, function ($query) use ($request) {
                $query->whereHas('advertisement', function ($q) {
                    $q->where('type', 'buy');
                })->where('status', $request->buy_status);
            })
            ->when($request->buy_sort, function ($query) use ($request) {
                $sort = $request->buy_sort;
                $query->orderBy('created_at', $sort === 'date_asc' ? 'asc' : 'desc');
            })
            ->when($request->rent_status, function ($query) use ($request) {
                $query->whereHas('advertisement', function ($q) {
                    $q->where('type', 'rent');
                })->where('status', $request->rent_status);
            })
            ->when($request->rent_sort, function ($query) use ($request) {
                $sort = $request->rent_sort;
                $query->orderBy('created_at', $sort === 'date_asc' ? 'asc' : 'desc');
            })
            ->get();
    
        return view('returns.index', compact('returns'));
    }

    protected function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
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
        return back()->with('success', __('messages.return_request_approved'));
    }

    public function reject($id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->update(['status' => 'rejected']);
        return back()->with('error', __('messages.return_request_rejected'));
    }

    public function store(Request $request, $id) {
        $request->validate([
            'reason' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $advertisement = Advertisement::findOrFail($id);

        if ($advertisement->acquirer_user_id !== auth()->id()) {
            return back()->with('error', __('messages.only_return_you_bought'));
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
                'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
                'reason' => $request->reason,
                'status' => 'approved',
            ]);

            return back()->with('success', __('messages.product_returned'));
        }

        $imagePath = $request->file('image') ? $request->file('image')->store('returns', 'public') : null;

        ReturnRequest::create([
            'advertisement_id' => $advertisement->id,
            'user_id' => auth()->id(),
            'image' => $imagePath,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', __('messages.return_request_sent'));
    }
}
