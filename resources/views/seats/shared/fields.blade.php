@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<x-field.input hidden name="theater_id"
                value="{{ $seat->theater->id }}"/>

<x-field.input name="row" label="Row" :readonly="$readonly"
                value="{{ old('row', $seat->row) }}"/>

<x-field.input name="seat_number" label="Seat Number" :readonly="$readonly"
                value="{{ old('seat_number', $seat->seat_number) }}"/>
