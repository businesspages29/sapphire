@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>{{ __('Matches') }}</h1>
        <a href="{{ route('home.create') }}" class="btn btn-primary">{{ __('Create') }}</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('Match') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                @endforeach
                @forelse($records as $record)
                    <tr>
                        <td>{{ __('Match') }}{{ $record->id }}</td>
                        <td>
                            {{-- <a href="{{ route('home.show', $record->id) }}" class="btn btn-primary">{{ __('View') }}</a> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">{{ __('No records found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
