<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Dashboard: laat de bands zien die de ingelogde gebruiker beheert
    public function index(Request $request)
    {
        $bands = $request->user()->bands()->orderBy('name')->get();

        return view('home', compact('bands'));
    }
}