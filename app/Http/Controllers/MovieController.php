<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MovieController extends Controller
{
    public function index(): View
    {
        $allMovies = Movie::all();
        return view('movies.index')->with('movies', $allMovies);
    }

    public function create(): View
    {
        $newMovie = new Movie();
        return view('movies.create')->withMovie($newMovie);
    }

    public function store(Request $request) : RedirectResponse
    {
        Movie::create($request->all());
        return redirect()->route('movies.index');
    }

    public function edit(Movie $movie): View
    {
        return view('movies.edit')->with('movie', $movie);
    }

    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $movie->update($request->all());
        return redirect()->route('movies.index');
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();
        return redirect()->route('movies.index');
    }

    public function show(Movie $movie): View
    {
        return view('movies.show')->with('movie', $movie);
    }
}
