@extends('layouts.admin')

@section('header-title', 'List of Movies')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-movies.filter-card
                :filterAction="route('movies.index')"
                :resetUrl="route('movies.index')"
                :genres="$listGenres"
                :genre="$filterByGenre"
                :keyword="$filterByKeyword"
                class="mb-6"
                />
            @if(auth()->user()->getTypeDescriptionAttribute() == 'Administrative')
            @can('create', App\Models\Movie::class)
                <div class="flex items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('movies.create') }}"
                        text="Create a new movie"
                        type="success"/>
                </div>
            @endcan
            @endif
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
            @if(auth()->user()->getTypeDescriptionAttribute() == 'Administrative')
                <x-movies.table :movies="$movies"
                    :showGenre="true"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            @else
                <x-movies.table :movies="$movies"
                    :showGenre="true"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    />
            @endif
            </div>
            <div class="mt-4">
                {{ $movies->appends(request()->query())->links() }}
            </div>
            @empty($movies->items())
                <div class="col-md-12 text-center my-auto" style="padding-top: 9em; padding-bottom: 9em">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <h3 class="card-title">NÃO HÁ FILMES EM EXIBIÇÃO PARA O QUE PESQUISOU</h3>
                    </div>
                </div>
            @endempty
        </div>
    </div>
@endsection

