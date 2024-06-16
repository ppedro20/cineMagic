@extends(Auth::User()?->type === 'A' ? 'layouts.admin' : 'layouts.main')

@section('header-title', Auth::User()?->type === 'A' ? 'List of Purchases' : 'My Purchases')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                @if (Auth::User()?->type !== 'A')
                    <x-purchases.filter-card
                    :filterAction="route('purchases.index')"
                    :resetUrl="route('purchases.index')"
                    :before="$filterByBefore"
                    :after="$filterByAfter"
                    class="mb-6" />
                @else
                    <x-purchases.filter-card
                    :filterAction="route('purchases.index')"
                    :resetUrl="route('purchases.index')"
                    :showKeyword="true" :keyword="$filterByKeyword"
                    :before="$filterByBefore"
                    :after="$filterByAfter"
                    class="mb-6" />
                @endif

                <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                    @if (Auth::User()?->type === 'A')
                        <x-purchases.table :purchases="$purchases" :showCustomers="true" />
                    @else
                        <x-purchases.table :purchases="$purchases" />
                    @endif
                </div>
                <div class="mt-4">
                    {{ $purchases->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endsection
