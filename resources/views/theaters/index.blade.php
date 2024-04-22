@extends('layout')
@section('header-title', 'List of Theaters')
@section('main')
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Photo Filename</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($theaters as $theater)
                <tr>
                    <td>{{ $theater->name }}</td>
                    <td>{{ $theater->photo_filename }}</td>
                    <td>
                        <a href="{{ route('theaters.show', ['theater' => $theater]) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
