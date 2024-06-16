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

    $payment_options = [''=>'-', 'MBWAY' => 'MBWAY', 'VISA' => 'VISA', 'PAYPAL' => 'PAYPAL'];
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{!! old('name', $customer->name) !!}"/>
        <x-field.input name="email" type="email" label="Email" :readonly="$readonly"
                        value="{{ old('email', $customer->email) }}"/>
        <x-field.input name="nif" type="number" label="NIF (optional)" width="lg" :readonly="$readonly"
                value="{{ old('nif', $customer->customer?->nif) }}" />

        <x-field.select id="payment_type" onchange="changeType(this)" name="payment_type" label="Payment Type" width="lg" :readonly="$readonly"
            value="{{ old('payment_type', $customer->customer?->payment_type ?? '') }}"
            :options="$payment_options" />

        <x-field.input id="payment_ref" name="payment_ref" label="Payment Ref" width="lg" :readonly="$readonly"
            value="{{ old('payment_ref', $customer->customer?->payment_ref ?? '') }}" />
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

<script type="text/javascript">

    function changeType() {
        var payment_ref = document.getElementById('id_payment_type');
        var payment_ref = document.getElementById('id_payment_ref');


        var selectedPaymentType = id_payment_type.value;

        var type;
        if (selectedPaymentType === 'MBWAY' ||selectedPaymentType === 'VISA') {
            type = 'number';
        } else {
            type = 'email';
        }
        payment_ref.type = type;
    }

    changeType()
</script>
