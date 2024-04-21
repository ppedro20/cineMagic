<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movie</title>
</head>

<body>
    <h2>Movie "{{ $movie->title }}"</h2>
    <div>
        @include('movies.shared.fields', ['readonlyData' => true])
    </div>
</body>

</html>
