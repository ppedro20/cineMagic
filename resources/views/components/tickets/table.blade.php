@php
$config = App\Models\Configuration::first();
    $price = Auth::User()?->type === 'C'?
        $config->ticket_price - $config->registered_customer_ticket_discount
        :$config->ticket_price;

    $total_price = $tickets->count() * $price;
@endphp

<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                @isset($showId)
                    @if ($showId)
                        <th class="px-2 py-2 text-left">Ticket</th>
                    @endif
                @endisset
                @isset($showCustomers)
                    @if ($showCustomers)
                        <th class="px-2 py-2 text-left">Customer Name</th>
                        <th class="px-2 py-2 text-left">Email</th>
                    @endif
                @endisset
                <th class="px-2 py-2 text-left">Movie</th>
                <th class="px-2 py-2 text-left">Theater</th>
                <th class="px-2 py-2 text-left">Poster</th>
                <th class="px-2 py-2 text-left">Seat</th>
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">Start at</th>
                <th class="px-2 py-2 text-left">Price</th>
                @if ($showStatus)
                    <th class="px-2 py-2 text-left">Status</th>
                @endif
                @isset($showView)
                    @if ($showView)
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
                    @isset($showId)
                        @if ($showId)
                            <td class="px-2 py-2 text-left">{{ $ticket->id }}</td>
                        @endif
                    @endisset
                    @isset($showCustomers)
                        @if ($showCustomers)
                            <td class="px-2 py-2 text-left">{{ $ticket->purchase->customer_name }}</td>
                            <td class="px-2 py-2 text-left">{{ $ticket->purchase->customer_email }}</td>
                        @endif
                    @endisset
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->theater->name }}</td>
                    <td class="px-2 py-2 text-left">
                        <img class="md h-28" src="{{ $ticket->screening->movie->posterFullUrl }}" alt="Movie Poster"
                            height="100px" />
                    </td>
                    <td class="px-2 py-2 text-left">{{ $ticket->seat->name }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->date }}</td>
                    <td class="px-2 py-2 text-left">
                        {{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('H:i') }}
                    </td>
                    <td class="px-2 py-2 text-left">{{ $ticket->price }}&#8364;</td>
                    @if ($showStatus)
                        @if ($ticket->isValid)
                            <th class="px-2 py-2 text-left">
                                <x-button element="a" text="Valid" type="success" />
                            </th>
                        @else
                            <th class="px-2 py-2 text-left">
                                <x-button element="a" text="Invalid" type="danger" />
                            </th>
                        @endif
                    @endif
                    @isset($showRemoveFromCart)
                        @if ($showRemoveFromCart)
                            <td>
                                <x-table.icon-minus class="px-0.5" method="delete"
                                    action="{{ route('cart.remove', ['screening' => $ticket->screening, 'seat' => $ticket->seat]) }}" />
                            </td>
                        @endif
                    @endisset
                    @isset($showView)
                        @if ($showView)
                            <td>
                                <x-table.icon-show class="ps-3 px-0.5"
                                    href="{{ route('tickets.show', ['ticket' => $ticket]) }}" />
                            </td>
                        @endif
                    @endisset
                </tr>
            @endforeach
            @isset($showTotal)
                @if ($showTotal)
                    <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left">Total</td>
                        <td class="px-2 py-2 text-left">{{ $ticket->purchase?->total_price?? $total_price }}&#8364;</td>
                    </tr>
                @endif
            @endisset
        </tbody>
    </table>
</div>
