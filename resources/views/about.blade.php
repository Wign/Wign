@extends('layout.main')

@section('title', __( 'common.Wign.About' ) )
@section('open_graph')
    @include('layout.openGraph', [ 'title' => __( 'common.Wign.About' ), 'url' => url( config('wign.urlPath.about') ) ])
@stop

@section('content')
    {{-- Opret oversættelsesfil --}}
    <h1>Om Wign</h1>
    <p>Nuværende version: <a href="{{ config('social.github.url') }}"
                             title="Wign hos Github">{{ config('wign.version') }}</a></p>
    <p><span class="brand">Wign</span> er en social tegnsprogsencyklopædi. Formålet med siden er, at alle kan bidrage og
        dele tegn med hinanden: Tegn for fagbegreber, stednavn, bestemte personer og andre ord der ikke er hyppigt
        benyttet i døves dagligdag. Alt tegn, der ligger her kommer fra tegnsprogsbrugere, der gerne vil dele tegn med
        hinanden. Her kan du se, oprette og efterlyse tegn for en bestemt ord. En form for Wikipedia over dansk
        tegnsprog (og det skal <em>ikke</em> forveksles med <a
                href="{{config('externalurl.tegnsprog.url')}}">{{config('externalurl.tegnsprog.text')}}</a>).</p>
    <p>Selve grundidéen bag Wign.dk er inspireret af en blogindlæg af Kenneth Andersen på Danske Døve Ungdomsforbund's
        blog. Kenneth beskrev en fælles tegnbank, hvor alle er med til at bidrage med tegn. Navnet <span class="brand">Wign</span>
        er en akronym af <span class="brand">Wi</span>kipedia og Si<span class="brand">gn</span>.</p>
    <p>Projektet blev dengang gennemført af DDU og 12K, men fejlede desværre på grund af kompliceret setup og
        kommunikationsproblemer med hjemmesideleverandøren. Wign blev lagt på hylden, indtil Troels Madsen tog over og
        senere kom Kenneth Andersen med i projektet.
        Målet med denne version er at gøre hele systemet transparant. Den underliggende kode er åbent, og selve
        processen er ikke hemmeligholdt. Du har som bruger en stor indblik i projektet, hjemmesiden og folket bag siden.
        Og du er med til at forme hjemmesiden ved at bidrage med indhold og/eller idéer og forslag.</p>
    <p>Projektet er blevet til en virksomhed som i dag ejer af Troels Madsen, Kenneth Andersen og ITU-BD, som brænder for at
        berige adgang til tegnsprog på tvær af hverdag- og fagsamtaler. Gennem fælleskab kan vi bevare vores værdifulde
        kulturarv: <span
                class="brand">Dansk tegnsprog</span>. Projektet er støttet af <a
                href="http://duf.dk/tilskud-og-stoette/dufs-initiativstoette/">DUFs InitiativStøtte</a>.

        <a href="http://duf.dk/tilskud-og-stoette/dufs-initiativstoette/"><img src="{{asset('images/duf-is.png')}}"
                                                                               alt="DUFs intativstøtte logo"
                                                                               class="wign logo-index" width="400"></a>

    <p class="hand"><img src="{{ asset('images/hands-up.png') }}">Ikonerne her på siden er lavet af <a
                href="http://firehill.dk/">André Jensen</a>

    <p>Du kan finde projektet på <a href="{{ config('social.github.url') }}">Github.com</a>. Github’s primære funktion
        er at gøre det let at dele kode og samarbejde om projekter. Du er mere end velkommen til at deltage og bidrage
        med kodestumper eller rettelser til <span class="brand">Wign</span>'s rygrad.</p>
    <p>Du kan følge os på vores <a href="{{ config('social.facebook.url') }}">Facebook side</a>.</p>
    <p>Wign driver af  <a
                href="mailto:{{ config('wign.email') }}" title="Send Kenneth og Troels en mail">Kenneth Andersen og Troels Madsen</a>.</p>
    <img src="{{asset('images/wign_logo_new.png')}}" alt="Wign logo" class="wign logo-index">
@stop
