<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TheaterController extends Controller
{
    public function index(): View
    {
        $allTheaters = Theater::all();
        return view('theaters.index')->with('theaters', $allTheaters);
    }

    public function show(Theater $theater): View
    {
        return view('theaters.show')->with('theater', $theater);
    }
}
