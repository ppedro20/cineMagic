@extends( Auth::user()?->type == 'A' ?'layouts.admin':'layouts.main')

@section('header-title', $movie->title)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                @if(Auth::user()?->type == 'A')
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Movie::class)
                        <x-button
                            href="{{ route('movies.create') }}"
                            text="New"
                            type="success"/>
                    @endcan
                    @can('update', $movie)
                        <x-button
                            href="{{ route('movies.edit', ['movie' => $movie]) }}"
                            text="Edit"
                            type="primary"/>
                    @endcan
                    @can('delete', $movie)
                        <form method="POST" action="{{ route('movies.destroy', ['movie' => $movie]) }}">
                            @csrf
                            @method('DELETE')
                            <x-button
                                element="submit"
                                text="Delete"
                                type="danger"/>
                        </form>
                    @endcan
                @endif
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Movie "{{ $movie->title }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('movies.shared.fields', ['mode' => 'show', 'listGenres' => $movie->genre->pluck('name', 'code')->toArray()])
                </div>
                    <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                        Screenings
                    </h3>
                    <x-screenings.table
                    :screenings="$screenings"
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


