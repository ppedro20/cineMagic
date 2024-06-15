@extends('layouts.main')

@section('header-title', 'Screenings')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-screenings.filter-card
                :filterAction="route('screenings.showscreenings')"
                :resetUrl="route('screenings.showscreenings')"
                :movie="$filterByMovie"
                :theater="$filterByTheater"
                :before="$filterByBefore"
                :after="$filterByAfter"
                class="mb-6"
                />
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-screenings.table :screenings="$screenings"
                    :showMovie="true"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    />
            </div>
            <div class="mt-4">
                {{ $screenings->links() }}
            </div>
        </div>
    </div>
@endsection

