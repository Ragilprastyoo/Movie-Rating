<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function index()
    {
    $movies = Movie::query()
                ->when(request('search'), function($query) {
                    $query->where('title', 'like', '%'.request('search').'%');
                })
                ->when(request('genre'), function($query) {
                    $query->where('genre', request('genre'));
                })
                ->when(request('year'), function($query) {
                    $query->whereYear('release_year', request('year'));
                })
                ->latest()
                ->paginate(10);

    return view('movies.index', [
        'movies' => $movies,
        'genres' => Movie::distinct()->pluck('genre')
    ]); 
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'release_year' => 'required|integer|min:1900|max:'.(date('Y')+5),
            'genre' => 'required',
            'rating' => 'required|numeric|min:0|max:10',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'director' => 'required',
            'duration_minutes' => 'required|integer|min:1'
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster_url'] = $request->file('poster')->store('posters', 'public');
        }

        Movie::create($validated);

        return redirect()->route('movies.index')
                        ->with('success', 'Movie created successfully.');
    }

    public function show(Movie $movie)
    {
        return view('movies.show', [
            'movie' => $movie,
            'reviews' => $movie->reviews()->with('user')->latest()->paginate(5)
        ]);
    }

    public function edit(Movie $movie)
    {
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'release_year' => 'required|integer|min:1900|max:'.(date('Y')+5),
            'genre' => 'required',
            'rating' => 'required|numeric|min:0|max:10',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'director' => 'required',
            'duration_minutes' => 'required|integer|min:1'
        ]);

        if ($request->hasFile('poster')) {
            if ($movie->poster_url) {
                Storage::disk('public')->delete($movie->poster_url);
            }
            $validated['poster_url'] = $request->file('poster')->store('posters', 'public');
        }

        $movie->update($validated);

        return redirect()->route('movies.show', $movie)
                        ->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster_url) {
            Storage::disk('public')->delete($movie->poster_url);
        }

        $movie->delete();

        return redirect()->route('movies.index')
                        ->with('success', 'Movie deleted successfully.');
    }

    public function search(Request $request)
    {
        $movies = Movie::when($request->q, function($query) use ($request) {
                    $query->where('title', 'like', '%'.$request->q.'%')
                          ->orWhere('description', 'like', '%'.$request->q.'%');
                })
                ->limit(5)
                ->get();

        return response()->json($movies);
    }
}