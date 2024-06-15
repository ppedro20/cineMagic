

<div>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                @isset($showMovie)
                    @if ($showMovie)
                        <th class="px-2 py-2 text-left">Movie</th>
                    @endif
                @endisset
                <th class="px-2 py-2 text-left">Theater</th>
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">Starts at</th>
                <td class="px-2 py-2 text-left">Occupation</td>
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
                @php
                    $occupation = $screening->occupation();
                    $reservedSeats = $occupation[0];
                    $totalSeats = $occupation[1];
                @endphp
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    @isset($showMovie)
                        @if ($showMovie)
                            <td class="px-2 py-2 text-left">{{ $screening->movie->title }}</td>
                        @endif
                    @endisset
                    <td class="px-2 py-2 text-left">{{ $screening->theater->name }}</td>
                    <td class="px-2 py-2 text-left">{{ $screening->date }}</td>
                    <td class="px-2 py-2 text-left">{{ \Carbon\Carbon::parse($screening->start_time)->format('H:i') }}</td>
                    <td class="px-2 py-2 text-left">{{ $reservedSeats.'/'.$totalSeats }}</td>
                    @isset($showView)

                            <td>
                                @if ($showView)
                                    <x-table.icon-show class="ps-3 px-0.5"
                                        href="{{ route('screenings.show', ['screening' => $screening]) }}" />
                                @else
                                    <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                                @endif
                            </td>

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
