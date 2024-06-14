<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Customer Name</th>
                <th class="px-2 py-2 text-left">Email</th>
                <th class="px-2 py-2 text-left">Movie</th>
                <th class="px-2 py-2 text-left">Screening</th>
                <th class="px-2 py-2 text-left">Seat</th>
                <th class="px-2 py-2 text-left">Price</th>
                <th class="px-2 py-2 text-left">Status</th>

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
            @foreach ($tickets as $ticket)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $ticket->purchase->customer_name }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->purchase->customer_email }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->seat->name}}</td>
                    @isset($showView)
                        @if ($showView)
                            <td>
                                @can('view', $ticket)
                                    <x-table.icon-show class="ps-3 px-0.5"
                                        href="{{ route('tickets.show', ['ticket' => $ticket]) }}" />
                                @else
                                    <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showEdit)
                        @if ($showEdit)
                            <td>
                                @can('update', $ticket)
                                    <x-table.icon-edit class="px-0.5"
                                        href="{{ route('tickets.edit', ['ticket' => $ticket]) }}" />
                                @else
                                    <x-table.icon-edit class="px-0.5" :enabled="false" />
                                @endcan
                            </td>
                        @endif
                    @endisset
                    @isset($showDelete)
                        @if ($showDelete)
                            <td>
                                @can('delete', $ticket)
                                    <x-table.icon-delete class="px-0.5"
                                        action="{{ route('tickets.destroy', ['ticket' => $ticket]) }}" />
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
