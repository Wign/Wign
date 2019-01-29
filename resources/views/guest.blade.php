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
    <h1>Profil:</h1>

    <p> Navn: {{ $user->name }}</p>
    <p> Oprettet d. {{ $user->created }}</p>
    <p> Antal optagne tegn: {{ $user->videoCreatorCount }}</p>

    @if(Auth::user()->isAdmin())
        <button id="btnBan" class="btn" style="background-color:#f1a899" onclick="location.href='{{ route('admin.ban', $user->id) }}'">
            {{__('text.guest.ban')}}
        </button>
    @else
        <button id="btnPromote" class="btn" style="background-color:aquamarine" onclick="location.href='{{ route('promotion.new', $user->id) }}'">
            {{__('text.guest.promote')}}
        </button>

        <button id="btnDemote" class="btn" style="background-color:#f1a899" onclick="location.href='{{ route('demotion.new', $user->id) }}'">
            {{__('text.guest.demote')}}
        </button>
    @endif


@endsection