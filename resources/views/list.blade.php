@extends('layout.main')

@section('title', 'Seneste '.$number.' tegn')
@section('open_graph')
    @include('layout.openGraph', ['title' => 'Seneste '.$number.' tegn', 'url' => url('/seneste'), 'desc' => 'De seneste '.$number.' tegn som er blevet lagt op i Wign. Her kan du få en kort kig i aktivitetet på Wign i den seneste tid.'])
@stop

@section('content')
<h1>Seneste {{ $number }} tegn</h1>

    <ul>
    @foreach($words as $word)
        <li>{{-- date("d-m-Y", $word->updated_at->timestamp) // Ved ikke om det skal bruges? --}} <a href="{{ URL::to('/tegn').'/'.Helper::makeUrlString($word->word) }}">{{ $word->word }}</a></li>
    @endforeach
    </ul>
    <a href="{{ URL::to('/alle') }}" class="float--right" title="Alle vores tegn">Se alle vores tegn</a>

@stop
