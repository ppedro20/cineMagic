@extends('layouts.admin')

@section('header-title', 'Screening of '.$screening->movie->title)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Screening::class)
                        <x-button
                            href="{{ route('screenings.create') }}"
                            text="New"
                            type="success"/>
                    @endcan
                    @can('update', $screening)
                        <x-button
                            href="{{ route('screenings.edit', ['screening' => $screening]) }}"
                            text="Edit"
                            type="primary"/>
                    @endcan
                    @can('delete', $screening)
                        <form method="POST" action="{{ route('screenings.destroy', ['screening' => $screening]) }}">
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
                        Screening of "{{ $screening->movie->title }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('screenings.shared.fields',['mode' => 'show', 'listTheaters' => $screening->theater->pluck('name', 'id')->toArray(), 'listMovies' => $screening->movie->pluck('title', 'id')->toArray()])
                </div>
                <x-seats.table-reserved
                    :seats="$screening->theater->seats"
                    :screeningId="$screening->id"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    class="pt-4"
                    />

            </section>
        </div>
    </div>
</div>
@endsection


