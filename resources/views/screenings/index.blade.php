@extends('layouts.admin')

@section('header-title', 'List of Screenings')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-screenings.filter-card
                :filterAction="route('screenings.index')"
                :resetUrl="route('screenings.index')"
                :movie="$filterByMovie"
                :theater="$filterByTheater"
                :before="$filterByBefore"
                :after="$filterByAfter"
                class="mb-6"
                />
            @can('create', App\Models\Screening::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('screenings.create') }}"
                        text="Create a new screening"
                        type="success"/>
                </div>
            @endcan
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-screenings.table :screenings="$screenings"
                    :showMovie="true"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            </div>
            <div class="mt-4">
                {{ $screenings->links() }}
            </div>
        </div>
    </div>
@endsection

