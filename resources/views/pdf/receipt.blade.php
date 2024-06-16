<!DOCTYPE html>
<html>
<head>
    <title>{{ $purchase->id }}</title>
</head>
<body>
    <div class="mt-12">
        <div class="flex justify-between space-x-12 items-end">
            <div>
                <h3 class="mb-4 text-xl">Purchase</h3>
                <div class="flex flex-col w-full md:w-1/2 gap-6">
                    <div> Name: {{ $purchase->customer_name }} </div>
                    <div> Email: {{ $purchase->customer_email }} </div>
                    <div> NIF:{{$purchase->nif ?? '-' }} </div>
                    <div> Payment Type:{{$purchase->payment_type}} </div>
                </div>
            </div>
        </div>
    </div>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Ticket</th>
                <th class="px-2 py-2 text-left">Movie</th>
                <th class="px-2 py-2 text-left">Poster</th>
                <th class="px-2 py-2 text-left">Seat</th>
                <th class="px-2 py-2 text-left">Date</th>
                <th class="px-2 py-2 text-left">Start at</th>
                <th class="px-2 py-2 text-left">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->tickets as $ticket)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left">{{ $ticket->id }}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->movie->title }}</td>
                    <td class="px-2 py-2 text-left">
                        <img class="md h-28" src="{{ $ticket->screening->movie->posterFullUrl }}" alt="Movie Poster" height="100px"/>
                    </td>
                    <td class="px-2 py-2 text-left">{{ $ticket->seat->name}}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->screening->date }}</td>
                    <td class="px-2 py-2 text-left">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('H:i')}}</td>
                    <td class="px-2 py-2 text-left">{{ $ticket->price }}&#8364;</td>
                </tr>
            @endforeach
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">Total</td>
                <td class="px-2 py-2 text-left">{{ $purchase->total_price }}&#8364;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
