@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>{{ __('Teams with Match Round') }}</h1>
        <h3>{{ __('Match' . $data['match']->id) }}</h3>
        <h3>{{ __('Round' . $data['round']) }}</h3>

        <h5>{{ __('Teams') }}</h5>
        <h5>{{ __('Winner') }}</h5>
        <ul class="list-group">
            @foreach ($data['winnerTeams'] as $team)
                <li class="list-group-item">{{ $team->name }}</li>
            @endforeach
        </ul>
        @if ($data['winnerTeams']->count() != 0)
            <h5>{{ __('Wild Card') }}</h5>
            <ul class="list-group">
                @foreach ($data['wildcardTeams'] as $item)
                    <li class="list-group-item">{{ $item->name }}</li>
                @endforeach
            </ul>
        @endif
        <div class="mt-3">
            <p>{{ __('Round ' . (request('round') + 1)) }}</p>
            <a class="btn btn-primary"
                href="{{ route('home.matchRound', ['id' => request('id'), 'round' => request('round') + 1]) }}">Go
                to Match Round</a>
        </div>
    </div>
@endsection
