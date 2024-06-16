@extends('layouts.admin')

@section('header-title', 'List of Employees')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-employees.filter-card
                :filterAction="route('employees.index')"
                :resetUrl="route('employees.index')"
                :name="$filterByName"
                class="mb-6"
                />
            @can('create', App\Models\User::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('employees.create') }}"
                        text="Create a new employee"
                        type="success"/>
                </div>
            @endcan
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-employees.table :employees="$employees"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            </div>
            <div class="mt-4">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
