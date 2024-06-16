@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
    $choosefile = !$readonly;
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{!! old('name', $employee->name) !!}"/>
        <x-field.input name="email" type="email" label="Email" :readonly="$readonly"
                        value="{!! old('email', $employee->email) !!}"/>
    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_file"
            label="Photo"
            width="md"
            :choosefile="$choosefile"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($employee->photo_filename)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$employee->photoFullUrl"/>
    </div>
</div>
