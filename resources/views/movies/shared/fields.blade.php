@php
 $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div>
    <label for="inputTitle">Title</label>
    <input type="text" name="title" id="inputTitle" {{ $disabledStr }} value="{{ $movie->title }}">
</div>
<div>
    <label for="inputGenreCode">Genre Code</label>
    <input type="text" name="genre_code" id="inputGenreCode" {{ $disabledStr }} value="{{ $movie->genre_code }}">
</div>
<div>
    <label for="inputYear">Year</label>
    <input type="year" name="year" id="inputYear" {{ $disabledStr }} value="{{ $movie->year }}">
</div>
<div>
    <label for="inputPosterFilename">Poster Filename</label>
    <input type="file" name="poster_filename" id="inputPosterFilename" {{ $disabledStr }} value="{{ $movie->poster_filename }}">
</div>
<div>
    <label for="inputSynopsis">Synopsis</label>
    <input type="text" name="synopsis" id="inputSynopsis" {{ $disabledStr }} value="{{ $movie->synopsis }}">
</div>
<div>
    <label for="inputTrailerURL">Trailer URL</label>
    <input type="text" name="trailer_url" id="inputTrailerURL" {{ $disabledStr }} value="{{ $movie->trailer_url }}">
</div>
