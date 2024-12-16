@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>{{ __('Teams') }}</h1>
        <form method="POST" action="{{ route('home.store') }}">
            @csrf
            @for ($i = 1; $i <= $data['maxTeams']; $i++)
                <div class="mb-3">
                    <label for="team{{ $i }}" class="form-label">{{ __('Team') }} {{ $i }}</label>
                    <input type="text" class="form-control" id="team{{ $i }}" name="team[{{ $i }}]"
                        {{-- value="{{ old('team.' . $i) }}" --}} value="Team {{ $i }}" required>
                    @error('team.' . $i)
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endfor
            <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
        </form>
    </div>
@endsection
