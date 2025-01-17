@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<x-field.input hidden name="theater_id"
                value="{{ $seat->theater->id }}"/>

<div class="appearance-none block
            mt-1 w-full
            bg-white dark:bg-gray-900
            text-black dark:text-gray-50">
            Theater {{ $seat->theater->name }}</div>

<div class="grow mt-6 space-y-4">
    <div class="flex gap-4">
        <div>
            <x-field.input name="row" label="Row" :readonly="$readonly"
                value="{{ old('row', $seat->row) }}"/>
        </div>
        <div>
            <x-field.input name="seat_number" label="Seat Number" :readonly="$readonly"
                value="{{ old('seat_number', $seat->seat_number) }}"/>
        </div>
    </div>
</div>



