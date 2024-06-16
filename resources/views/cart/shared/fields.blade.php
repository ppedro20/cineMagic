@php
    $payment_options = [''=>'-', 'MBWAY' => 'MBWAY', 'VISA' => 'VISA', 'PAYPAL' => 'PAYPAL'];
@endphp


<div class="flex flex-wrap space-x-8">

    @if (Auth::User())
        <div>
            <x-field.input name="customer_name" type="text" label="Name" width="lg" class hidden
                value="{{ old('customer_name', Auth::User()->name) }}" />


            <x-field.input name="customer_email" type="email" label="Email" width="lg" hidden
                value="{{ old('customer_email', Auth::User()->email) }}" />

            <x-field.input name="nif" type="number" label="NIF (optional)" width="lg" :readonly="false"
                value="{{ old('nif', Auth::User()?->customer?->nif) }}" />
        </div>

        <div>
            <x-field.select id="payment_type" onchange="changeType(this)" name="payment_type" label="Payment Type" width="lg" :readonly="false"
                value="{{ old('payment_type', Auth::User()?->customer?->payment_type ?? '') }}"
                :options="$payment_options" />

            <x-field.input id="payment_ref" name="payment_ref" label="Payment Ref" width="lg" :readonly="false"
                value="{{ old('payment_ref', Auth::User()?->customer?->payment_ref ?? '') }}" />
        </div>

    @else
        <div>
            <x-field.input name="customer_name" type="text" label="Name" width="lg" :readonly="false"
                value="{{ old('customer_name') }}" />

            <x-field.input name="customer_email" type="email" label="Email" width="lg" :readonly="false"
                value="{{ old('customer_email') }}" />

            <x-field.input name="nif" type="number" label="NIF (optional)" width="lg" :readonly="false"
                value="{{ old('nif') }}" />
        </div>

        <div>
            <x-field.select onchange="changeType(this)" name="payment_type" label="Payment Type" width="lg" :readonly="false"
                value="{{ old('payment_type') }}"
                :options="$payment_options" />

            <x-field.input name="payment_ref" label="Payment Ref" width="lg" :readonly="false"
                value="{{ old('payment_ref') }}" />
        </div>
    @endif
</div>
<x-button element="submit" type="dark" text="Confirm" class="mt-4" />

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
