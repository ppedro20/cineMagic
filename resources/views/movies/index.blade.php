@extends('layout')
@section('header-title', 'List of Movies')
@section('main')
 <p>
 <a href="{{ route('movies.create') }}">Add a new Movie</a>
 </p>
 <table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Genre-Code</th>
            <th>Year</th>
            <th>Poster_Filename</th>
            <th>Synopsis</th>
            <th>Trailer_URL</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($movies as $movie)
            <tr>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->genre_code }}</td>
                <td>{{ $movie->year }}</td>
                <td>{{ $movie->poster_filename }}</td>
                <td>{{ $movie->synopsis }}</td>
                <td>{{ $movie->trailer_url }}</td>
                <td>
                    <a href="{{ route('movies.show', ['movie' => $movie]) }}">View</a>
                </td>
                <td>
                    <a href="{{ route('movies.edit', ['movie' => $movie]) }}">Edit</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('movies.destroy', ['movie' => $movie]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
