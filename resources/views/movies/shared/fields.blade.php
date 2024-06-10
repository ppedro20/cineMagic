@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<x-field.input name="title" label="Title" width="md"
                :readonly="$readonly || ($mode == 'edit')"
                value="{{ old('title', $movie->title) }}"/>

<x-field.input name="genre_code" label="Genre Code" :readonly="$readonly"
                value="{{ old('genre_code', $movie->genre_code) }}"/>

<x-field.input name="year" label="Year" :readonly="$readonly"
                value="{{ old('year', $movie->year) }}"/>

<x-field.input name="poster_filename" label="Poster Filename" :readonly="$readonly"
                value="{{ old('poster_filename', $movie->poster_filename) }}"/>

<x-field.input name="synopsis" label="Synopsis" :readonly="$readonly"
                value="{{ old('synopsis', $movie->synopsis) }}"/>


<x-field.input name="genretrailer_url_code" label="Genretrailer Url Code" :readonly="$readonly"
                value="{{ old('genretrailer_url_code', $movie->genretrailer_url_code) }}"/>
