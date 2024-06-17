@extends( Auth::user()?->type == 'A' ?'layouts.admin':'layouts.main')

@section('header-title', 'Screening of '.$screening->movie->title)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('validate', App\Models\Ticket::class)
                        <x-button
                            href="{{ route('tickets.validate', ['screening' => $screening]) }}"
                            text="ACCESS CONTROL"
                            type="warning"/>
                    @endcan
                    @can('create', App\Models\Screening::class)
                        <x-button
                            href="{{ route('screenings.create') }}"
                            text="New"
                            type="success"/>
                    @endcan
                    @can('update', $screening)
                        <x-button
                            href="{{ route('screenings.edit', ['screening' => $screening]) }}"
                            text="Edit"
                            type="primary"/>
                    @endcan
                    @can('delete', $screening)
                        <form method="POST" action="{{ route('screenings.destroy', ['screening' => $screening]) }}">
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
                        Screening of "{{ $screening->movie->title }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @if(Auth::user()?->type == 'A')
                        @include('screenings.shared.fields',[
                            'mode' => 'show',
                            'listTheaters' => $screening->theater->withTrashed()->pluck('name', 'id')->toArray(),
                            'listMovies' => $screening->movie->withTrashed()->pluck('title', 'id')->toArray()
                        ])
                    @else
                        @include('screenings.shared.fields',[
                            'mode' => 'show',
                            'listTheaters' => $screening->theater->pluck('name', 'id')->toArray(),
                            'listMovies' => $screening->movie->pluck('title', 'id')->toArray()
                        ])
                    @endif
                </div>

                @if(Auth::user()?->type == 'A')
                <x-seats.table-reserved
                    :seats="$screening->theater->seats()->withTrashed()->get()"
                    :screeningId="$screening->id"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    class="pt-4"
                    />
                @else
                <x-seats.table-tickets
                    :seats="$screening->theater->seats"
                    :screening="$screening"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    class="pt-4"
                    />

                @endif

            </section>
        </div>
    </div>
</div>

@endsection


