@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                @if($movie->poster_url)
                <img src="{{ asset($movie->poster_url) }}" class="card-img-top" alt="{{ $movie->title }}">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $movie->title }}</h1>
                    <p class="text-muted">
                        {{ $movie->release_year }} | {{ $movie->duration_minutes }} menit
                    </p>
                    <p><strong>Genre:</strong> {{ $movie->genre }}</p>
                    <p><strong>Sutradara:</strong> {{ $movie->director }}</p>
                    <p><strong>Rating:</strong> {{ $movie->rating }}/10</p>
                    <hr>
                    <h4>Sinopsis</h4>
                    <p class="card-text">{{ $movie->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('movies.index') }}" class="btn btn-secondary">
        Kembali ke Daftar Film
    </a>
    @auth
    <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning">
        Edit Film
    </a>
    @endauth
</div>
@endsection