@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
    $adminReadonly = $readonly;
    if (!$adminReadonly) {
        if ($mode == 'create') {
            $adminReadonly = Auth::user()?->cannot('createCustomer', App\Models\User::class);
        } elseif ($mode == 'edit') {
            $adminReadonly = Auth::user()?->cannot('updateCustomer', $customer);
        } else {
            $adminReadonly = true;
        }
    }
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{{ old('name', $customer->name) }}"/>
        <x-field.input name="email" type="email" label="Email" :readonly="$readonly"
                        value="{{ old('email', $customer->email) }}"/>
        <x-field.radiogroup name="gender" label="Gender" :readonly="$readonly"
            value="{{ old('gender', $customer->gender) }}"
            :options="['M' => 'Masculine', 'F' => 'Feminine']"/>
    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_file"
            label="Photo"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($customer->photo_url)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$customer->photoFullUrl"/>
    </div>
</div>
