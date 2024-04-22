@extends('layout')
@section('header-title', 'List of Movies')
@section('main')
    <div class="flex justify-center">
        <div
            class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="flex items-center gap-4 mb-4">
                <x-hyperlink-text-button href="{{ route('movies.create') }}" text="Add a new movie" type="success" />
            </div>
            <table class="table-auto border-collapse">
                <thead>
                    <tr
                        class="border-b-2 border-b-gray-400 dark:border-b-gray-500
                    bg-gray-100 dark:bg-gray-800">
                        <th class="px-2 py-2 text-left">Title</th>
                        <th class="px-2 py-2 text-left">Genre-Code</th>
                        <th class="px-2 py-2 text-left">Year</th>
                        <th class="px-2 py-2 text-left">Poster_Filename</th>
                        <th class="px-2 py-2 text-left">Synopsis</th>
                        <th class="px-2 py-2 text-left">Trailer_URL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movies as $movie)
                        <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                            <td class="px-2 py-2 text-left">{{ $movie->title }}</td>
                            <td class="px-2 py-2 text-left">{{ $movie->genre_code }}</td>
                            <td class="px-2 py-2 text-left">{{ $movie->year }}</td>
                            <td class="px-2 py-2 text-left">{{ $movie->poster_filename }}</td>
                            <td class="px-2 py-2 text-left">{{ $movie->synopsis }}</td>
                            <td class="px-2 py-2 text-left">{{ $movie->trailer_url }}</td>
                            <td>
                                <x-table.icon-show class="ps-3 px-0.5"
                                    href="{{ route('movies.show', ['movie' => $movie]) }}" />
                            </td>
                            <td>
                                <x-table.icon-edit class="px-0.5"
                                    href="{{ route('movies.edit', ['movie' => $movie]) }}" />
                            </td>
                            <td>
                                <x-table.icon-delete class="px-0.5"
                                    action="{{ route('movies.destroy', ['movie' => $movie]) }}" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
