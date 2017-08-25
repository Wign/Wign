@extends('layout.main')

@section('title', 'Seneste '.$antal.' tegn')
@section('open_graph')
    @include('layout.openGraph', ['title' => 'Seneste '.$antal.' tegn', 'url' => url('/seneste'), 'desc' => 'De seneste '.$antal.' tegn som er blevet lagt op i Wign. Her kan du få en kort kig i aktivitetet på Wign i den seneste tid.'])
@stop

@section('content')
<h1>Seneste {{ $antal }} tegn</h1>
    
    <ul>
    @foreach($words as $word)
        <li>{{-- date("d-m-Y", $word->updated_at->timestamp) // Ved ikke om det skal bruges? --}} <a href="{{ URL::to('/tegn').'/'.($word->word) }}">{{ $word->word }}</a></li>
    @endforeach
    </ul>
    <a href="{{ URL::to('/alle') }}" class="float--right" alt="Alle vores tegne">Se alle vores tegn</a>
    
@stop
