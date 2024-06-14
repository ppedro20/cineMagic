@extends(Auth::user() ? 'layouts.admin':'layouts.main')

@section('header-title', $seat->row.$seat->seat_number)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\seat::class)
                        <x-button
                            href="{{ route('seats.create', ['theaterId' => $seat->theater->id]) }}"
                            text="New"
                            type="success"/>
                    @endcan
                    @can('update', $seat)
                        <x-button
                            href="{{ route('seats.edit', ['seat' => $seat]) }}"
                            text="Edit"
                            type="primary"/>
                    @endcan
                    @can('delete', $seat)
                        <form method="POST" action="{{ route('seats.destroy', ['seat' => $seat]) }}">
                            @csrf
                            @method('DELETE')
                            <x-button
                                element="submit"
                                text="Delete"
                                type="danger"/>
                        </form>
                    @endcan
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        seat "{{ $seat->row.$seat->seat_number }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('seats.shared.fields', ['mode' => 'show'])
                </div>
            </section>
        </div>
    </div>
</div>
@endsection


