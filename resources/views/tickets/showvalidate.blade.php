@extends('layouts.main')

@section('header-title', 'Validating Screening of '.$screening->movie->title.' on '.$screening->theater->name)

@section('main')
@isset($alert_msg)
    @if ($alert_msg)
        <x-alert type="{{ $alert_type ?? 'info' }}">
            {!! $alert_msg !!}
        </x-alert>
    @endif
@endisset

<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Insert Ticket ID
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    <div class="grow mt-6 space-y-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col w-full md:w-1/2 gap-6">

                                <form method="GET" id="ticketForm" action="{{ route('tickets.validate', ['screening' => $screening->id]) }}">
                                    <x-field.input name="ticket_id" type="number" label="Ticket ID" width="full" :readonly="false" value="" />
                                    <div class="flex flex-left mt-6">
                                        <x-button element="submit" text="Validate" type="primary" onclick="appendTicketId()" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
