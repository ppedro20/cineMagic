@extends(Auth::User()?->type === 'A'?'layouts.admin':'layouts.main')

@section('header-title', Auth::User()?->type === 'A'?'List of Tickets':'My Tickets')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                @if (Auth::User()?->type === 'A')
                    <x-tickets.filter-card
                        :filterAction="route('tickets.index')"
                        :resetUrl="route('tickets.index')"
                        :showKeyword="true"
                        :keyword="old('keyword', $filterByKeyword)"
                        :movie="old('movie', $filterByMovie)"
                        :theater="old('theater', $filterByTheater)"
                        class="mb-6"
                        />
                @else
                    <x-tickets.filter-card
                        :filterAction="route('tickets.index')"
                        :resetUrl="route('tickets.index')"
                        :movie="old('movie', $filterByMovie)"
                        :theater="old('theater', $filterByTheater)"
                        class="mb-6"
                        />
                @endif

            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                @if (Auth::User()?->type === 'A')
                    <x-tickets.table :tickets="$tickets"
                        :showView="true"
                        :showCustomers="true"
                        />
                @else
                    <x-tickets.table :tickets="$tickets"
                        :showView="true"
                        :showStatus="true"
                        />
                @endif
            </div>
            <div class="mt-4">
                {{ $tickets->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
