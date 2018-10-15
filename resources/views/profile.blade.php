@extends('layout.main')
@section('open_graph')
    @include('layout.openGraph')
@stop

@section('content')
    <h1>@lang('common.user.profile')</h1>

    <h2>Personlig information</h2>
    <p> Email: {{ $user->email }}</p>
    <p> Antal optagne tegn: {{ $user->videos()->count() }}</p>

    <h2>Afventede afstemninger</h2>
    @if( $awaitings->isEmpty() )
        <p> Ingen afventede afstemninger, sådan! </p>
    @else
        @foreach($awaitings as $awaiting)
            <?php $awaiting ?>
        @endforeach
    @endif

    <h2>Afventede afstemning af mine bidragelser</h2>
    @if( $awaitings->isEmpty() )
        <p> Alle dine bidragelser er klar </p>
    @else
        @foreach($user->reviewAuthor() as $post)
            <?php $post ?>
        @endforeach
    @endif

@endsection