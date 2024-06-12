<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Title</th>
                @isset($showGenre)
                    @if ($showGenre)
                        <th class="px-2 py-2 text-left">Genre</th>
                    @endif
                @endisset
                <th class="px-2 py-2 text-left">Year</th>
                <th class="px-2 py-2 text-left">Poster Filename</th>
                <th class="px-2 py-2 text-left">Synopsis</th>
                <th class="px-2 py-2 text-left">Trailer Url</th>

                @isset($showView)
                    @if ($showView)
                        <th></th>
                    @endif
                @endisset
                @isset($showEdit)
                    @if ($showEdit)
                        <th></th>
                    @endif
                @endisset
                @isset($showDelete)
                    @if ($showDelete)
                        <th></th>
                    @endif
                @endisset
            </tr>
        </thead>
        <tbody>
            @foreach ($movies as $movie)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left">{{ $movie->title }}</td>
                    @isset($showGenre)
                        @if ($showGenre)
                            <td class="px-2 py-2 text-left">{{ $movie->genre()->name }}</td>
                        @endif
                    @endisset
                    <td class="px-2 py-2 text-left">{{ $movie->year }}</td>
                    <td class="px-2 py-2 text-left"><img src="{{ $movie->posterFullUrl }}" width="67" height="100" alt="Photo Movie" /></td>
                    <td class="px-2 py-2 text-left">{{ $movie->synopsis }}</td>
                    <td class="px-2 py-2 text-left"><a class="no-underline hover:underline" href="{{ $movie->trailer_url }}">{{ $movie->trailer_url }}</a></td>
                    @isset($showView)
                        @if ($showView)
                            <td>
                                @can('view', $movie)
                                    <x-table.icon-show class="ps-3 px-0.5"
                                        href="{{ route('movies.show', ['movie' => $movie]) }}" />
                                @else
                                    <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showEdit)
                        @if ($showEdit)
                            <td>
                                @can('update', $movie)
                                    <x-table.icon-edit class="px-0.5"
                                        href="{{ route('movies.edit', ['movie' => $movie]) }}" />
                                @else
                                    <x-table.icon-edit class="px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showDelete)
                        @if ($showDelete)
                            <td>
                                @can('delete', $movie)
                                    <x-table.icon-delete class="px-0.5"
                                        action="{{ route('movies.destroy', ['movie' => $movie]) }}" />
                                @else
                                    <x-table.icon-delete class="px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
