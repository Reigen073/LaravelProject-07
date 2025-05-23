<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('admin.contracts.index', compact('users'));
    }
 
    public function upload(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract' => 'required|file|mimes:pdf|max:10240',
            'contract_name' => 'required|string|max:255',

        ]);

        $path = $request->file('contract')->store('contracts', 'public');

        $contract = new Contract();
        $contract->user_id = $request->user_id;
        $contract->contract_name = $request->contract_name; 
        $contract->file_path = $path;
        $contract->save();

        return redirect()->route('contracts.index')->with('success', __('messages.contract_uploaded'));
    }


}

