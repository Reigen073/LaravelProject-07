<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    // Toon de contractenpagina
    public function index()
    {
        $users = User::all();  // Haal alle gebruikers op
        return view('admin.contracts.index', compact('users'));
    }

    // Upload een contract
    public function upload(Request $request)
    {
        // Validatie van de gegevens
        $request->validate([
            'user_id' => 'required|exists:users,id', // Zorg ervoor dat de geselecteerde gebruiker bestaat
            'contract' => 'required|file|mimes:pdf,docx|max:10240', // Maximaal 10MB voor het contract
        ]);

        // Opslaan van het bestand
        $path = $request->file('contract')->store('contracts', 'public'); // Sla het bestand op in de 'contracts' directory

        // Sla het contract op in de database
        $contract = new Contract();
        $contract->user_id = $request->user_id;
        $contract->file_path = $path;
        $contract->save();

        return redirect()->route('contracts.index')->with('success', 'Contract succesvol geüpload!');
    }
}

