@extends(Auth::User()?->type === 'A'?'layouts.admin':'layouts.main')

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
                                @if (Auth::User()?->type === 'A')
                                    <x-field.input name="customer_name" type="text" label="Name" width="full" :readonly="true"
                                        value="{{ $purchase->customer_name }}" />

                                    <x-field.input name="customer_email" type="email" label="Email" width="full" :readonly="true"
                                        value="{{ $purchase->customer_email }}" />
                                @endif


                                <x-field.input name="nif" type="number" label="NIF" width="full" :readonly="true"
                                    value="{{ $purchase->nif ?? '-' }}" />


                                <x-field.input name="payment_type" label="Payment Type" width="lg" :readonly="true"
                                    value="{{ $purchase->payment_type }}"/>

                                <x-field.input name="total_price" label="Total Price" width="lg" :readonly="true"
                                    value="{{ $purchase->total_price }}"/>

                            </div>
                            <div class="flex gap-4">
                            </div>
                        </div>
                        <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Tickets
                        </h3>
                        @if (Auth::User()?->type === 'A')
                            <x-tickets.table :tickets="$purchase->tickets"
                                :showView="true"
                                :showCustomers="false"
                                />
                        @else
                            <x-tickets.table :tickets="$purchase->tickets"
                                :showView="true"
                                :showStatus="true"
                                />
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
