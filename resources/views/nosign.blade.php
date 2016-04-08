<?php
if($word) { $titel = ucfirst($word); }
?>

@extends('layout.main')

@section('title')
{{{ $titel or 'Wign' }}}
@stop

@section('content')
<h1>{{{ $titel or 'Ukendt' }}}</h1>
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