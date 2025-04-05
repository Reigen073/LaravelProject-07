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
        // Validatie van de gegevens - alleen PDF toegestaan
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract' => 'required|file|mimes:pdf|max:10240', // Alleen PDF, maximaal 10MB
            'contract_name' => 'required|string|max:255', // Name of the contract

        ]);

        // Opslaan van het bestand
        $path = $request->file('contract')->store('contracts', 'public');

        // Sla het contract op in de database
        $contract = new Contract();
        $contract->user_id = $request->user_id;
        $contract->contract_name = $request->contract_name; 
        $contract->file_path = $path;
        $contract->save();

        return redirect()->route('contracts.index')->with('success', 'Contract succesvol geüpload!');
    }


}

