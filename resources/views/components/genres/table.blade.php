<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Code</th>
                <th class="px-2 py-2 text-left">Name</th>
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
            @foreach ($genres as $genre)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $genre->code }}</td>
                    <td class="px-2 py-2 text-left">{{ $genre->name }}</td>
                    @isset($showView)
                        @if ($showView)
                            <td>
                                @can('view', $genre)
                                    <x-table.icon-show class="ps-3 px-0.5"
                                        href="{{ route('genres.show', ['genre' => $genre]) }}" />
                                @else
                                    <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showEdit)
                        @if ($showEdit)
                            <td>
                                @can('update', $genre)
                                    <x-table.icon-edit class="px-0.5"
                                        href="{{ route('genres.edit', ['genre' => $genre]) }}" />
                                @else
                                    <x-table.icon-edit class="px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showDelete)
                        @if ($showDelete)
                            <td>
                                @can('delete', $genre)
                                    <x-table.icon-delete class="px-0.5"
                                        action="{{ route('genres.destroy', ['genre' => $genre]) }}" />
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
