@extends('layout.main')
@section('open_graph')
    @include('layout.openGraph')
@stop

@section('content')
    <h1>@lang('common.user.profile')</h1>

    <h2>Personlig information</h2>
    <p> Email: {{ $user->email }}</p>
    <p> Nuværende niveau: {{ $user->qcvs()->first()->rank }}</p>
    <p> Antal optagne tegn: {{ $user->videos()->count() }}</p>

    <h2>Afventede afstemninger</h2>
    @if( $count == 0 )
        <p> Ingen afventede afstemninger, sådan! </p>
    @else
        {{$count}} afventede afstemninger.
        <button id="btnVote" class="btn" onclick="location.href='{{ route('vote.index') }}'">
            {{__('text.vote.go')}}
        </button>
    @endif

    <h2>Afventede afstemning af mine bidragelser</h2>
    @if( isset($pendings) )
        <p> Alle dine bidragelser er klar </p>
    @else
        <p> Dine {{$pendings}} bidragelser afventes stadigvæk</p>
        {{--
        @foreach($user->reviewAuthor as $review)
            <li>"{{$review->withTrashed()->newIL->first()->post->word->word}}" - (Siden d. {{ $review->created_at->toFormattedDateString()}})</li>
        @endforeach
        --}}
    @endif

    @if(!$user->isEntry())
        <button id="btnPromote" class="btn" style="background-color:aquamarine" onclick="location.href='{{ route('promotion.new', $user->id) }}'">
            {{__('text.guest.promote')}}
        </button>
    @endif

@endsection