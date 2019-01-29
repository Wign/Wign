@extends('layout.main')
@section('open_graph')
    @include('layout.openGraph')
@stop

@section('content')
    {{$DEBUG = config('global.debug')}}
    @if($DEBUG)
        DEBUG: <br>
        ID: {{$user->id}} <br>
    @endif
    <h1>Admin Panel</h1>

    <h2>Afventede afstemninger</h2>
    {{$voting_count}} afventede afstemninger.
    <button id="btnVote" class="btn" onclick="location.href='{{ route('admin.vote') }}'">
        {{__('text.vote.go')}}
    </button>

@endsection