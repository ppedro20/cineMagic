@extends('layouts.main')

@section('header-title', 'Purchase '.$purchase->id)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Purchase "{{ $purchase->id }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    <div class="grow mt-6 space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col w-full md:w-1/2 gap-6">
                                <div> Name: {{ $purchase->customer_name }} </div>
                    <div> Email: {{ $purchase->customer_email }} </div>
                    <div> NIF:{{$purchase->nif ?? '-' }} </div>
                    <div> Payment Type:{{$purchase->payment_type}} </div>

                                <x-field.input name="customer_name" type="text" label="Name" width="full" :readonly="true"
                                    value="{{ old('customer_name',  $purchase->purchase->customer_name) }}" />

                                <x-field.input name="nif" type="number" label="NIF (optional)" width="full" :readonly="true"
                                    value="{{ old('nif', $purchase->purchase?->nif) }}" />

                                <x-field.input name="customer_email" type="email" label="Email" width="full" :readonly="true"
                                    value="{{ old('customer_email', $purchase->purchase->customer_email) }}" />
                                    <x-field.input name="customer_email" type="email" label="Email" width="full" :readonly="true"
                                    value="{{ old('customer_email', $purchase->purchase->customer_email) }}" />


                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
