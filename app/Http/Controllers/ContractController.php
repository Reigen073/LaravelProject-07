<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    // Toon de contractenpagina
    public function index()
    {
        $users = User::all();  // Haal alle gebruikers op
        //paginatiom at 6
        $users = User::paginate(6); // Haal alle gebruikers op met paginatie
        return view('admin.contracts.index', compact('users'));
    }
// Upload een contract
public function upload(Request $request)
{
    // Validatie van de gegevens - alleen PDF toegestaan
    $request->validate([
        'user_id' => 'required|exists:users,id', // Zorg ervoor dat de geselecteerde gebruiker bestaat
        'contract' => 'required|file|mimes:pdf|max:10240', // Alleen PDF, maximaal 10MB
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

