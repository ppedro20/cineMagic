<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                @isset($showCustomers)
                    @if ($showCustomers)
                        <th class="px-2 py-2 text-left">Customer Name</th>
                        <th class="px-2 py-2 text-left">Email</th>
                    @endif
                @endisset
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">NIF</th>
                <th class="px-2 py-2 text-left">Total Tickets</th>
                <th class="px-2 py-2 text-left">Total Price</th>
                <th class="px-2 py-2 text-left">Payment Type</th>
                <th class="px-2 py-2 text-left">Show</th>
                <th class="px-2 py-2 text-left">Receipt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    @isset($showCustomers)
                        @if ($showCustomers)
                            <td class="px-2 py-2 text-left">{{$purchase->customer_name}}</td>
                            <td class="px-2 py-2 text-left">{{$purchase->customer_email}}</td>
                        @endif
                    @endisset
                    <td class="px-2 py-2 text-left">{{ $purchase->date }}</td>
                    <td class="px-2 py-2 text-left">{{ $purchase->nif?? '-' }}</td>
                    <td class="px-2 py-2 text-left">{{ $purchase->tickets->count() }}</td>
                    <td class="px-2 py-2 text-left">{{ $purchase->total_price }}&#8364;</td>
                    <td class="px-2 py-2 text-left">{{ $purchase->payment_type }}</td>
                    <td class="px-2 py-2 text-left">
                        <x-table.icon-show class="ps-3 px-0.5"
                            href="{{ route('purchases.show', ['purchase' => $purchase]) }}" />
                    </td>
                    <td class="px-2 py-2 text-left">
                        <x-table.icon-show class="ps-3 px-0.5"
                            href="{{ route('purchases.receipt', ['purchase' => $purchase]) }}" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
