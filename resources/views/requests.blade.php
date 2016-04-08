@extends('layout.main')

@section('title')
Wign - Efterlyst tegne
@stop

@section('content')
<h1>Efterlyste tegn</h1>
@if(Session::has('message'))
    @if(Session::has('url'))
        <a href="{{ Session::get('url') }}">
    @endif
    <span class="msg--flash">{{ Session::get('message') }}</span>
    @if(Session::has('url'))
        </a>
    @endif
@endif
<ul>
@foreach($requests as $request)
    <li><a href="{{ URL::to('/tegn/'.$request->word) }}" alt="{{ $request->word }}">{{ $request->word }}</a> - {{ $request->request_count }} stemmer</li>
@endforeach
</ul>

@stop