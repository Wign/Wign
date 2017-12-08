@extends('layout.main')

@section('title', 'Du er på vores sortliste')

@section('content')

    <div class="buffer">

        <img src="{{asset('images/wign_logo.png')}}" alt="Wign logo" class="wign logo-index" width="269" height="85">
        <h1 class="headline">Desværre</h1>

        <p>Din IP-adresse <strong class="brand">{{ Session::get('ip') }}</strong> er på vores sortliste af følgende
            årsag:<br><span class="brand">{{ Session::get('reason') }}</span></p>
        <p>Vi tolererer desværre ikke upassede adfærd på Wign. Vi har valgt at indføre en nul-tolerance, og derfor er du
            blevet afvist adgangen til Wign.</p>
        <p>Er det tale om en fejl, og vil du have adgangen igen, så er du mere end velkommen til at kontakte os på <a
                    href="mailto:{{ config('wign.email') }}">mail</a>.</p>
    </div>

@stop
