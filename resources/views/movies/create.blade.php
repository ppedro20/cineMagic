<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movie</title>
</head>

<body>
    <h2>New Movie</h2>
    <form method="POST" action="{{route('movies.store')}}">
        @csrf
        @include('movies.shared.fields')
        <div>
            <button type="submit" name="ok">Save new course</button>
        </div>
    </form>
</body>

</html>
