@extends('layouts.main')

@section('header-title', 'Ticket '.$ticket->id)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Ticket "{{ $ticket->id }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    <div class="grow mt-6 space-y-4">
                        <div class="flex gap-4">
                            @can('validate', App\Models\Ticket::class)
                                <form method="POST" id="update_status" action="{{ route('tickets.updateStatus', ['ticket' => $ticket]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-button
                                        element="submit"
                                        text="Change Status"
                                        type="warning"/>
                                </form>
                            @endcan
                            <div class="flex flex-col w-full md:w-1/2 gap-6">
                                <x-field.input name="movie" label="Movie" width="full" :readonly="true"
                                    value="{{ old('movie', $ticket->screening->movie->title) }}" />

                                <x-field.input name="date" type="date" label="Date" width="full" :readonly="true"
                                    value="{{ old('date', $ticket->screening->date) }}" />

                                <x-field.input name="seat" label="Seat" width="full" :readonly="true"
                                    value="{{ old('seat' , $ticket->seat->name) }}" />

                                <x-field.input name="customer_name" type="text" label="Name" width="full" :readonly="true"
                                    value="{{ old('customer_name',  $ticket->purchase->customer_name) }}" />

                                <x-field.input name="nif" type="number" label="NIF (optional)" width="full" :readonly="true"
                                    value="{{ old('nif', $ticket->purchase?->nif) }}" />


                            </div>
                            <div class="flex flex-col w-full md:w-1/2 gap-6">
                                <x-field.input name="theater" label="Theater" width="full" :readonly="true"
                                    value="{{ old('theater', $ticket->screening->theater->name) }}" />

                                <x-field.input name="start_time" type="time" label="Start at" width="full" :readonly="true"
                                    value="{{ old('start_time', $ticket->screening->start_time? \Carbon\Carbon::parse($ticket->screening->start_time)->format('H:i') : '' ) }}" />

                                <x-field.input name="status" label="Status" width="full" :readonly="true"
                                    value="{{ old('status', $ticket->status) }}" />

                                <x-field.input name="customer_email" type="email" label="Email" width="full" :readonly="true"
                                    value="{{ old('customer_email', $ticket->purchase->customer_email) }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
