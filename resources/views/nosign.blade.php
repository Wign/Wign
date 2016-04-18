<?php
if(isset($word)) { 
    $title = 'Vi mangler tegnet for '.ucfirst($word);
    $desc = 'Wign har desværre ikke tegnet for '.$word.'. Du kan hjælpe Wign med at bidrage med et tegn til '.$word.'.';
    $url = url('/tegn/'.$word);
} 
else { 
    $title = 'Vi mangler et tegn';
    $desc = 'Wign har desværre ikke et tegn. Du kan hjælpe Wign med din bidrag.';
    $url = url('/tegn');
}
?>

@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc])
@stop

@section('content')
<h1>{{ $title }}</h1>
<p><span class="brand">Wign</span> har desværre ikke tegnet{{{ isset($word) ? ' for '.$word : ''}}}. Du kan nu enten:
    <ul>
        <li><a href="{{ URL::to('/opret/'.$word) }}" alt="opret tegnet for {{ $word }}">Oprette {{ isset($word) ? 'tegnet for '.$word : 'tegnet'}}</a></li>eller
        <li><a href="{{ URL::to('/request/'.$word) }}">Efterlyse {{ isset($word) ? 'tegnet for '.$word : 'tegnet'}}</a></li>
    </ul>
</p>

@if($word)
<p>
<h3>Eller mente du et af disse tegn?</h3>
    <ul>
@foreach($suggestions as $suggest)
        <li><a href="{{ URL::to('/tegn/'.$suggest) }}">{{ $suggest }}</a></li>
@endforeach
    </ul>
</p>
@endif
@stop