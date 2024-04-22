@extends('layout')
@section('header-title', 'Update movie "' . $movie->title . '"')
@section('main')
    <form method="POST" action="{{ route('movies.update', ['movie' => $movie->id]) }}">
        @csrf
        @method('PUT')
        @include('movies.shared.fields')
        <div>
            <button type="submit" name="ok">Save movie</button>
        </div>
    </form>
@endsection
