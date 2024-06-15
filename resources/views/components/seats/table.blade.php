@php
    $seatsByRow = App\Models\Seat::seatsPerRow($seats);
@endphp



<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left hidden lg:table-cell">Row</th>
                <th class="px-2 py-2 text-left">Seats</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seatsByRow as $row => $seats)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $row }}</td>
                @foreach ($seats as $seat)
                    <td class="px-2 py-2 text-left">
                        <div class="flex items-center justify-start">
                            @isset($showView)
                                    @if ($showView)
                                        @can('view', $seat)
                                        <x-button element='a' href="{{ route('seats.show', ['seat' => $seat]) }}"
                                            text="{{ $seat->seat_number }}"
                                            type="primary" />
                                        @endcan
                                    @else
                                        <x-button element='a' href="#"
                                            text="{{ $seat->seat_number }}"
                                            type="primary" />

                                    @endif
                                @endisset


                            <div class="flex flex-col px-0.5">
                                @isset($showEdit)
                                    @if ($showEdit)
                                        @can('update', $seat)
                                        <x-table.icon-edit class="px-0.5"
                                            href="{{ route('seats.edit', ['seat' => $seat]) }}" />
                                        @else
                                            <x-table.icon-edit class="px-0.5" :enabled="false" />
                                        @endcan
                                    @endif
                                @endisset
                                @isset($showDelete)
                                    @if ($showDelete)
                                        @can('delete', $seat)
                                            <x-table.icon-delete class="px-0.5"
                                                action="{{ route('seats.destroy', ['seat' => $seat]) }}" />
                                        @else
                                            <x-table.icon-delete class="px-0.5" :enabled="false" />
                                        @endcan
                                    @endif
                                @endisset
                            </div>
                        </div>
                    </td>
                @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
