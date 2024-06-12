<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MovieFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MovieController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Movie::class);
    }

    public function index(Request $request): View
    {
        $moviesQuery = Movie::orderBy('title');
        $filterByTitle = $request->query('title');
        if ($filterByTitle) {
            $moviesQuery->where('title', 'like', "%$filterByTitle%");
        }
        $filterByGenre = $request->query('genre');
        if ($filterByGenre) {
            $moviesQuery->where('genre_code', "$filterByGenre");
        }
        $movies = $moviesQuery
            ->paginate(20)
            ->withQueryString();

        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();

        return view(
            'movies.index',
            compact('movies', 'listGenres', 'filterByTitle', 'filterByGenre')
        );
    }

    public function create(): View
    {
        $newMovie = new Movie();
        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();
        return view('movies.create')
            ->withMovie($newMovie)
            ->with('listGenres', $listGenres);
    }

    public function store(MovieFormRequest $request) : RedirectResponse
    {
        $validatedData = $request->validated();
        $newMovie = DB::transaction(function () use ($validatedData, $request) {
            $newMovie = new Movie();
            $newMovie->title = $validatedData['title'];
            $newMovie->genre_code = $validatedData['genre_code'];
            $newMovie->year = $validatedData['year'];
            $newMovie->synopsis = $validatedData['synopsis'];

            if ($request->hasFile('poster_file')) {
                $path = $request->poster_file->store('public/posters');
                $newMovie->poster_filename = basename($path);
            }

            $newMovie->save();
            return $newMovie;
        });

        $url = route('movies.show', ['movie' => $newMovie]);
        $htmlMessage = "Movie <a href='$url'><u>{$newMovie->title}</u></a> has been created successfully!";
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(Movie $movie): View
    {
        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();
        return view('movies.edit', compact('movie', 'listGenres'));
    }

    public function update(MovieFormRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();
        $movie = DB::transaction(function () use ($validatedData, $movie, $request) {
            $movie->title = $validatedData['title'];
            $movie->genre_code = $validatedData['genre_code'];
            $movie->year = $validatedData['year'];
            $movie->synopsis = $validatedData['synopsis'];

            if ($request->hasFile('poster_file')) {
                if ($movie->poster_filename && Storage::fileExists("public/posters/$movie->poster_filename")) {
                    Storage::delete("public/posters/$movie->poster_filename");
                }
                $path = $request->poster_file->store('public/posters');
                $movie->poster_filename = basename($path);

            }
            $movie->save();
            return $movie;
        });
        $url = route('movies.show', ['movie' => $movie]);
        $htmlMessage = "Movie <a href='$url'><u>{$movie->title}</u></a> has been updated successfully!";


        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $htmlMessage = "Movie <u>{$movie->title}</u> has been deleted successfully!";
        $movie->delete();
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroyPoster(Movie $movie): RedirectResponse
    {
        if ($movie->poster_filename && Storage::fileExists("public/posters/$movie->poster_filename")) {
            Storage::delete("public/posters/$movie->poster_filename");

            $movie->poster_filename = null;
            $movie->save();
            $url = route('movies.show', ['movie' => $movie]);
            return redirect()->route('movies.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "Poster of <a href='$url'><u>{$movie->title}</u></a> has been deleted.");
        }
        return redirect()->back();
    }

    public function show(Movie $movie): View
    {
        return view('movies.show', compact('movie'));
    }
}
