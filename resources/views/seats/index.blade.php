@extends('layouts.admin')

@section('header-title', 'List of Seats')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-seats.filter-card
                :rows="$listRows"
                :filterAction="route('seats.index')"
                :resetUrl="route('seats.index')"
                :row="old('row', $filterByRow)"
                class="mb-6"
                />
            @can('create', App\Models\Seat::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('seats.create', ['theaterId' => $seats[10]?->theater->id]) }}"
                        text="Create a new seat"
                        type="success"/>
                </div>
            @endcan
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-seats.table :seats="$seats"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            </div>
            <div class="mt-4">
                {{ $seats->links() }}
            </div>
        </div>
    </div>
@endsection

