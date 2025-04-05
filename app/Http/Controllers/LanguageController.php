<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function languageSwitch(Request $request)
    {
        $language = $request->language;
        Session::put('locale', $language);
        return redirect()->back();
    }
}