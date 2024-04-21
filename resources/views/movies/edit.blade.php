<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movie</title>
</head>

<body>
    <h2>Update Movie "{{ $movie->title }}"</h2>
    <form method="POST" action="{{ route('movies.update', ['movie' => $movie->id]) }}">
        @csrf
        @method('PUT')
        @include('movies.shared.fields')
        <div>
            <button type="submit" name="ok">Save movie</button>
        </div>
    </form>
</body>

</html>
