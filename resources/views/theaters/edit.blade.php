@extends('layouts.admin')

@section('header-title', $theater->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Theater::class)
                    <x-button
                        href="{{ route('theaters.create') }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('view', $theater)
                    <x-button
                        href="{{ route('theaters.show', ['theater' => $theater]) }}"
                        text="View"
                        type="info"/>
                    @endcan
                    @can('delete', $theater)
                    <form method="POST" action="{{ route('theaters.destroy', ['theater' => $theater]) }}">
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
                        Edit theater "{{ $theater->name }}"
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                        Click on "Save" button to store the information.
                    </p>
                </header>

                <form method="POST" action="{{ route('theaters.update', ['theater' => $theater]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('theaters.shared.fields', ['mode' => 'edit'])
                    </div>
                    <div class="flex mt-6">
                        <x-button element="submit" type="dark" text="Save" class="uppercase"/>
                        <x-button element="a" type="light" text="Cancel" class="uppercase ms-4"
                                    href="{{ url()->previous() }}"/>
                    </div>
                </form>
                <div class="flex flex-wrap justify-start items-center gap-4 mt-4">
                @can('create', App\Models\Seat::class)
                    <x-button
                        href="{{ route('seats.create', ['theaterId' => $theater->id]) }}"
                        text="Create Seat"
                        type="success"/>
                @endcan
                </div>



                <x-seats.table :seats="$theater->seats"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    class="pt-4"
                    />
            </section>
        </div>
    </div>
</div>
@endsection

