@extends('layouts.admin')

@section('header-title', $seat->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Seat::class)
                    <x-button
                        href="{{ route('seats.create', ['theaterId' => $seat->theater->id]) }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('view', $seat)
                    <x-button
                        href="{{ route('seats.show', ['seat' => $seat]) }}"
                        text="View"
                        type="info"/>
                    @endcan
                    @can('delete', $seat)
                    <form method="POST" action="{{ route('seats.destroy', ['seat' => $seat]) }}">
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
                        Edit seat "{{ $seat->name }}"
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                        Click on "Save" button to store the information.
                    </p>
                </header>

                <form method="POST" action="{{ route('seats.update', ['seat' => $seat]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('seats.shared.fields', ['mode' => 'edit'])
                    </div>
                    <div class="flex mt-6">
                        <x-button element="submit" type="dark" text="Save" class="uppercase"/>
                        <x-button element="a" type="light" text="Cancel" class="uppercase ms-4"
                                    href="{{ url()->previous() }}"/>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection

