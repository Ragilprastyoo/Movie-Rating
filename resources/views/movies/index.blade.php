@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Film</h1>

    <form class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="genre" class="form-control">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ $genre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="row">
        @foreach($movies as $movie)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $movie->title }}</h5>
                    <p class="card-text">
                        <small class="text-muted">{{ $movie->genre }} • {{ $movie->release_year }}</small>
                    </p>
                    <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-sm btn-primary">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $movies->links() }}
</div>
@endsection