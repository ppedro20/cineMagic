@php
    $config= App\Models\Configuration::first();
    $total=0;
    $price=Auth::User()?->customer? $config->ticket_price - $config->registered_customer_ticket_discount : $config->ticket_price;
    foreach($tickets as $t){
        $total+=$price;
    }
@endphp

<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Movie</th>
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
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->seat->name}}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->date }}</td>
                    <td class="px-2 py-2 text-left">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('H:i')}}</td>
                    <td class="px-2 py-2 text-left">{{ $price }}&#8364;</td>
                    @if ($showStatus)
                        @if($ticket->isValid)
                            <th class="px-2 py-2 text-left">
                                <x-button
                                    element="a"
                                    text="Valid"
                                    type="success"
                                />
                            </th>
                        @else
                            <th class="px-2 py-2 text-left">
                                <x-button
                                    element="a"
                                    text="Invalid"
                                    type="danger"
                                />
                            </th>
                        @endif
                    @endif

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
                </tr>
            @endforeach
            @isset($showTotal)
                @if ($showTotal)
                    <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left"></td>
                        <td class="px-2 py-2 text-left">Total</td>
                        <td class="px-2 py-2 text-left">{{ $total }}&#8364;</td>
                    </tr>
                @endif
            @endisset
        </tbody>
    </table>

</div>
