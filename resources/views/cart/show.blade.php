@extends('layouts.main')

@section('header-title', 'Shopping Cart')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">

            @empty($cart)
                <h3 class="text-xl w-96 text-center">Cart is Empty</h3>
            @else
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-tickets.table
                    :tickets="$cart"
                    :showView="false"
                    :showId="false"
                    :showStatus="false"
                    :showTotal="true"
                    :showRemoveFromCart="true"
                    />
            </div>
            <div class="mt-12">
                <div class="flex justify-between space-x-12 items-end">
                    <div>
                        <h3 class="mb-4 text-xl">Shopping Cart Confirmation </h3>
                        <form action="{{ route('cart.confirm') }}" method="post">
                            @csrf
                            @include('cart.shared.fields')
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('cart.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-button element="submit" type="danger" text="Clear Cart" class="mt-4"/>
                        </form>
                    </div>
                </div>
            </div>
            @endempty
        </div>
    </div>
@endsection
