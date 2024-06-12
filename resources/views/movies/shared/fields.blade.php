@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="title" label="Title" width="md" :readonly="$readonly"
            value="{{ old('title', $movie->title) }}" />

        <x-field.select name="genre_code" label="Genre" :readonly="$readonly"
            value="{{ old('genre_code', $movie->genre_code) }}"
            :options="$listGenres"/>

        <x-field.input name="year" label="Year" :readonly="$readonly"
            value="{{ old('year', $movie->year) }}" />


        <x-field.text-area name="synopsis" label="Synopsis" :readonly="$readonly"
            value="{{ old('synopsis', $movie->synopsis) }}" />


        <x-field.input name="trailer_url" label="Trailer Url" :readonly="$readonly"
            value="{{ old('trailer_url', $movie->trailer_url) }}" />
    </div>
    <div class="pb-6">
        <x-field.image
            name="poster_file"
            label="Poster"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Poster"
            :deleteAllow="($mode == 'edit') && ($movie->poster_filename)"
            deleteForm="form_to_delete_poster"
            :imageUrl="$movie->posterFullUrl"/>
    </div>
</div>
