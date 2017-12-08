@extends('layout.main')

@section('title', 'Retningslinjer')
@section('open_graph')
    @include('layout.openGraph', ['title' => 'Retningslinjer', 'url' => url('/retningslinjer'), 'desc' => 'Vores retningslinjer for brug af Wign'])
@stop

@section('content')
    {{-- Opret gerne en separat fil til oversættelse af disse --}}
    <h1>Retningslinjer</h1>
    <p>Brug altid det mest udbredt (og helst dansk) oversættelse af ordet i dets grundform (tjek gerne <a
                href="{{config('externalurl.ordnet.url')}}">{{config('externalurl.ordnet.text')}}</a> og <a
                    href="{{config('externalurl.ordbogen.url')}}">{{config('externalurl.ordbogen.text')}}</a> om ordet
            forefindes på dansk).</p>

    <h2>Acceptabel tegn</h2>
    <ul>
        <li>Ord</li>
        <li>Faste udtryk</li>
        <li>Højgrad leksikalitet (nogle bogstave undlades under stavelse eller MHS)</li>
    </ul>

    <h2>Ikke godkendt</h2>
    <ul>
        <li>Utydelig fremvisning af tegn</li>
        <li>Tegn til udelukket intern brug</li>
        <li>Mere end et tegn pr. video</li>
        <li>Tegn/ord som kan opdeles yderligere uden at ændre på konteksten</li>
        <li>En hel sætning</li>
        <li>Begreb som staves hele vejen explicit</li>
        <li>Tegn som allerede findes på <a href="{{config('externalurl.tegnsprog.url')}}">{{config('externalurl.tegnsprog.text')}}</a></li>
        <li>Tegn der ikke kan ses pga. nedsat videokvalitet og/eller belysning</li>
    </ul>

    <h2>Opfordring mht. optagelse</h2>
    <ul>
        <li>Vandret format</li>
        <li>Ikke håndholdt kamera</li>
        <li>Skal kunne se hele hænder i billedet når der siges VUGGE og HJORT</li>
        <li>God belysning</li>
    </ul>

    <h2>Tips</h2>
    <ul>
        <li>Drej til venstre/højre efter først visning og gentag</li>
        <li>Udfør tegn i rolig tempo</li>
    </ul>
    <p class="brand">Ved overtrædelse af overstående retningslinjer vil indholdet enten blive rettet eller fjernet uden
        varsel.</p>

@stop
