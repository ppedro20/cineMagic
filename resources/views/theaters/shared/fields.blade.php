@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<x-field.input name="name" label="Name" :readonly="$readonly"
                value="{{ old('name', $theater->name) }}"/>
