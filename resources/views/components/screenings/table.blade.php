<div>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Theater</th>
                <th class="px-2 py-2 text-left">Movie</th>
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">Starts at</th>
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
            @foreach ($screenings as $screening)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $screening->theater->name }}</td>
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $screening->date }}</td>
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ \Carbon\Carbon::parse($screening->start_time)->format('H:i') }}</td>
                    @isset($showView)
                        @if ($showView)
                            <td>
                                @can('view', $screening)
                                    <x-table.icon-show class="ps-3 px-0.5"
                                        href="{{ route('screenings.show', ['screening' => $screening]) }}" />
                                @else
                                    <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showEdit)
                        @if ($showEdit)
                            <td>
                                @can('update', $screening)
                                    <x-table.icon-edit class="px-0.5"
                                        href="{{ route('screenings.edit', ['screening' => $screening]) }}" />
                                @else
                                    <x-table.icon-edit class="px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showDelete)
                        @if ($showDelete)
                            <td>
                                @can('delete', $screening)
                                    <x-table.icon-delete class="px-0.5"
                                        action="{{ route('screenings.destroy', ['screening' => $screening]) }}" />
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
