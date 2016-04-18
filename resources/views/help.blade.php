@extends('layout.main')

@section('title', 'Hjælp')
@section('open_graph')
    @include('layout.openGraph', ['title' => 'Hjælp', 'url' => url('/help'), 'desc' => 'Wign\'s hjælpesektion, hvor du kan få hjælp og vejledning i hvordan du kan bruge Wign optimalt.'])
@stop

@section('content')
    
    <h1>Hjælp</h1>
    <p>Denne side er ikke færdig endnu...<br>Der er derfor desværre ikke en guide eller hjælepvideo på siden lige nu.</p>
    <p>Finder du nogle fejl på siden er du velkommen til at indsende en fejlbesked på vores <a href="{{ $gitURL }}/issues">Github</a> side, eller sende en mail til <a href="mailto:{{ $email }}" title="Send Troels en mail">Troels Madsen</a>.
    <p>Du er også selvfølgelig velkommen til at skrive på vores <a href="{{ $fbURL }}">Facebook side</a>!</p>
    <p>Tak for din tålmodighed!</p>
    
@stop