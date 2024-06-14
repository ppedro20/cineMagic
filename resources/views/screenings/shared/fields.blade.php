@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="grow mt-6 space-y-4">
    <div class="flex gap-4">
        <div>
            <x-field.select name="movie_id" label="Movie" width="md" :readonly="$readonly"
                value="{{ old('movie_id', $screening->movie_id) }}" :options="$listMovies" />
        </div>
        <div>
            <x-field.select name="theater_id" label="Theater" width="md" :readonly="$readonly"
                value="{{ old('theater_id', $screening->theater_id) }}" :options="$listTheaters" />
        </div>
    </div>
    <div class="flex gap-4">
        <div>
            <x-field.input name="date" type="date" label="Date" width="md" :readonly="$readonly"
                value="{{ old('date', $screening->date) }}" />
        </div>

        <div>
            <x-field.input name="start_time" type="time" label="Start at" width="md" :readonly="$readonly"
                value="{{ old('start_time', $screening->start_time? \Carbon\Carbon::parse($screening->start_time)->format('H:i') : '' ) }}" />
        </div>
    </div>
</div>
