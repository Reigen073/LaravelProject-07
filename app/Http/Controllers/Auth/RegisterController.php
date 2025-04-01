<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    
    /**
     * Show the registration form.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'in:gebruiker,particulier_adverteerder,zakelijke_adverteerder'],
        ]);

        $user = User::create([
            'name' => $request->name, // Add this line
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
            
        ]);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
