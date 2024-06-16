@extends('layouts.admin')

@section('header-title', 'Statistics')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h2 class="card-title
                        @if (request()->routeIs('statistics.index'))
                            float-left
                        @endif
                    "></h2>
                </div>
                <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                    <div class="px-4 py-5 sm:p-6">
                        <h1>Statistics</h1>
                    </div>
                    <div class="px-4 py-5 sm:p-6 flex">
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="total_users" label="Total Users" width="md" :readonly="true"
                                    value="{{$totalAllUsers }}" />
                        </div>
                        <div class="px-4 py-5 sm:p-6">

                                <x-field.input name="total_purchases" label="Total Purchases" width="md" :readonly="true"
                                    value="{{$totalPurchases }}" />
                        </div>
                    </div>
                    <div class="px-4 py-5 sm:p-6 flex">
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="total_purchases" label="Month Purchases" width="md" :readonly="true"
                                    value="{{$thisMonthPurchases }}" />
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="today_purchases" label="Today Purchases" width="md" :readonly="true"
                                    value="{{$todayPurchases }}" />
                        </div>
                    </div>
                    <div class="px-4 py-5 sm:p-6 flex">
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="most_watched_movie" label="Most Watched Movie" width="md" :readonly="true"
                                    value="{{$mostWatchedFilm->title}} ({{$mostWatchedFilm->total}})" />
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="most_visited_theater" label="Most Visited Theater" width="md" :readonly="true"
                                    value="{{$bestTheater->name}} ({{$bestTheater->total}})" />
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                                <x-field.input name="total_purchases" label="Most Watched Genre" width="md" :readonly="true"
                                    value="{{$bestGenre->name}} ({{$bestGenre->total}})" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
