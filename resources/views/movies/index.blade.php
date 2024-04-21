<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movies</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <h1>List of Movies</h1>
    <p><a href="{{ route('movies.create') }}">Add a new movie</a></p>
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
                        <a href="{{ route('movies.edit', ['movie' => $movie]) }}">Update</a>
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
</body>

</html>
