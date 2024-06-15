<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Movie</th>
                <th class="px-2 py-2 text-left">Seat</th>
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">Start at</th>

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
                @isset($showAddToCart)
                    @if ($showAddToCart)
                        <th></th>
                    @endif
                @endisset
                @isset($showRemoveFromCart)
                    @if ($showRemoveFromCart)
                        <th></th>
                    @endif
                @endisset
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->seat->name}}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->date }}</td>
                    <td class="px-2 py-2 text-left">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('H:i')}}</td>

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
                    @if ($showRemoveFromCart)
                        <td>
                            <div {{ $attributes->merge(['class' => 'hover:text-red-600']) }}>
                                <form method="POST" action="{{ route('cart.remove', ['screening' => $ticket->screening, 'seat'=>  $ticket->seat] ) }}"  class="w-6 h-6">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" name="minus" class="w-6 h-6">
                                        <svg  class="hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
