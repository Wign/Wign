@extends('layout.main')

@section('title')
Wign - Du er blevet blacklistet
@stop

@section('content')

    <div class="buffer">

        <img src="{{asset('images/wign_logo.png')}}" alt="Wign logo" class="wign logo-index" width="269" height="85">
        <h1 class="headline">Fuck dig!</h1>

        <p>Din IP-adresse <strong class="brand">{{ Session::get('ip') }}</strong> er blevet sortelistet af følgende årsag:<br><span class="brand">{{ Session::get('reason') }}</span></p>
        <p>Vi tolererer desværre slet ikke den slags her på Wign, og derfor er du blevet forbudt adgang hertil fremover.</p>
        <p>Mener du at du ikke har gjort det og vil gerne have en ord indført, kan du kontakte os på <a href="mailto:{{ $email }}">mail</a>.</p>
        <p>Ha ikke en god dag. Farvel!</p>

    </div>

@stop