@extends(Auth::user() ? 'layouts.admin':'layouts.main')

@section('header-title', $genre->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Genre::class)
                        <x-button
                            href="{{ route('genres.create') }}"
                            text="New"
                            type="success"/>
                    @endcan
                    @can('update', $genre)
                        <x-button
                            href="{{ route('genres.edit', ['genre' => $genre]) }}"
                            text="Edit"
                            type="primary"/>
                    @endcan
                    @can('delete', $genre)
                        <form method="POST" action="{{ route('genres.destroy', ['genre' => $genre]) }}">
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
                        Genre "{{ $genre->name }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('genres.shared.fields', ['mode' => 'show'])
                </div>
                 <!-- TODO @ can('viewAny', App\Models\Movie::class)  -->
            </section>
        </div>
    </div>
</div>
@endsection


