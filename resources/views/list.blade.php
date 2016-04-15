@extends('layout.main')

@section('title')
Seneste tegn
@stop

@section('content')
<h1>Seneste 25 tegn</h1> 
    
    <ul>
    @foreach($words as $word)
        <li>{{-- date("d-m-Y", $word->updated_at->timestamp) // Ved ikke om det skal bruges? --}} <a href="{{ URL::to('/tegn').'/'.GenerateUrl($word->word) }}">{{ $word->word }}</a></li>
    @endforeach
    </ul>
    <a href="{{ URL::to('/alle') }}" class="float--right" alt="Alle vores tegne">Se alle vores tegn</a>
    
@stop
