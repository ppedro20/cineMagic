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
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Total Users</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$totalAllUsers}}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Total Purchases</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$totalPurchases}}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Month Purchases</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$thisMonthPurchases}}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Today Purchases</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$todayPurchases}}</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Most Watched Movie</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$mostWatchedFilm->title}} ({{$mostWatchedFilm->total}}) </dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Most Visited Theater</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$bestTheater->name}} ({{$bestTheater->total}})</dd>
                        </dl>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Most Watched Genre</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{$bestGenre->name}} ({{$bestGenre->total}})</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
