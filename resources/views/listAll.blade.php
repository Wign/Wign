@extends('layout.main')

@section('title', 'Alle tegn')
@section('open_graph')
    @include('layout.openGraph', ['title' => 'Alle tegn', 'url' => url('/alle'), 'desc' => 'Oversigt over alle de tegn som er blevet lagt op i Wign. Her kan du få en samlet overblik over hvad Wign egentligt rummer.'])
@stop

@section('content')
<h1>Alle tegn</h1>
    
    <ol>
    @foreach($words as $word)
        <li><a href="{{{ URL::to('/tegn').'/'.GenerateUrl($word->word) }}}">{{{ $word->word }}}</a></li>
    @endforeach
    </ol>
    
@stop
