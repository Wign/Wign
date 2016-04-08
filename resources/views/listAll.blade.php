@extends('layout.main')

@section('title')
Alle tegn
@stop

@section('content')
<h1>Alle tegn</h1>
    
    <ol>
    @foreach($words as $word)
        <li><a href="{{{ URL::to('/tegn').'/'.GenerateUrl($word->word) }}}">{{{ $word->word }}}</a></li>
    @endforeach
    </ol>
    
@stop