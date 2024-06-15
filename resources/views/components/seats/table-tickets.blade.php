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
                            @if ($seat->isReserved($screening->id))
                                <x-button
                                    element="a"
                                    :text="$seat->seat_number"
                                    type="danger"
                                    href="#"
                                    />
                            @else
                                <x-button
                                    element="submit"
                                    :text="$seat->seat_number"
                                    type="primary"
                                    form="form_add_cart_{{$seat->name}}"
                                />
                                <form id="form_add_cart_{{$seat->name}}" class="hidden" method="POST" action="{{ route('cart.add', ['screening' => $screening, 'seat' => $seat]) }}">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </td>
                @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
