<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementAPIController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->query('user_id');
        $email = $request->query('email');
        $password = $request->query('password');
    
        if (!$user_id || !$email || !$password) {
            return response()->json(['message' => 'User ID, email, and password are required'], 400);
        }
        $user = \App\Models\User::where('id', $user_id)->where('email', $email)->first();
    
        if (!$user || !\Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $advertisements = Advertisement::where('user_id', $user_id)->get();
        return response()->json($advertisements);
    }
}
