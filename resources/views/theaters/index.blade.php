@extends('layouts.admin')

@section('header-title', 'List of Theaters')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-theaters.filter-card
                :filterAction="route('theaters.index')"
                :resetUrl="route('theaters.index')"
                :name="$filterByName"
                class="mb-6"
                />
            @can('create', App\Models\Theater::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('theaters.create') }}"
                        text="Create a new theater"
                        type="success"/>
                </div>
            @endcan
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-theaters.table :theaters="$theaters"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            </div>
            <div class="mt-4">
                {{ $theaters->links() }}
            </div>
        </div>
    </div>
@endsection

