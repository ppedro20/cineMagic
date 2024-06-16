@extends('layouts.admin')

@section('header-title', 'Administrative "' . $employee->name . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\User::class)
                    <x-button
                        href="{{ route('employees.create') }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('update', $employee)
                    <x-button
                        href="{{ route('employees.edit', ['employee' => $employee]) }}"
                        text="Edit"
                        type="primary"/>
                    @endcan
                    @can('delete', $employee)
                    <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                    @endcan
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Administrative "{{ $employee->name }}"
                    </h2>
                </header>
                @include('employees.shared.fields', ['mode' => 'show'])
            </section>
        </div>
    </div>
</div>
@endsection
