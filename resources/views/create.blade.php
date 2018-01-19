<?php
if ( isset( $word ) ) {
	$title = 'Opret et tegn for ' . $word;
	$desc  = 'Hjælp os med din bidrag til ' . $word . '. Send dit tegn for ' . $word . ' ind ved at bruge din mobil eller computer.';
	$url   = url( '/opret/' . $word );
} else {
	$title = 'Opret et tegn';
	$desc  = 'Hjælp os med din bidrag. Send dit tegn ind ved brug af din mobil eller computer.';
	$url   = url( '/opret' );
}
?>
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc])
@stop

@section('extra_head_scripts')
    @include('layout.cameratag')
    <script>
        /**
         * Observe on the camera
         * @param   {recordingStarted}
         * @return  {disable button}    Disable the submit button
         * @return  {set alert}         Set a alert on before unload - telling the video is not be published yet
         */
        CameraTag.observe('wign01', 'recordingStarted', function () {
            // Disable the submit button (until the video is published)
            $('#btnSubmit').attr('disabled', true);

            // Set a alert on before unload
            $(window).bind('beforeunload', function () {
                return 'Pas på! Din video er ikke blevet lagt op endnu!\nHusk klik på "Indsend tegnet".';
            });
        });

        /**
         * Observe the camera
         * @param  {published}
         * @return {enable button}     On publish, enable the submit button again!
         */
        CameraTag.observe('wign01', 'published', function () {
            $('#btnSubmit').prop('disabled', false);
        });

        $(document).ready(function () {
            // Unbinding the beforeunload event fired before if clicked on the submit button
            $('form').submit(function () {
                $(window).unbind("beforeunload");
            });

            // Add tooltip UI to the document
            $(document).tooltip({
                position: {my: "center bottom", at: "center top-5", collision: "none"}
            });
        });

    </script>
@stop

@section('content')
    <h1>{{ $title }}</h1>
    @if(Session::has('message'))
        @if(Session::has('url'))
            <a href="{{ Session::get('url') }}">
                @endif
                <span class="msg--flash">{{ Session::get('message') }}</span>
                @if(Session::has('url'))
            </a>
        @endif
    @endif
    @if (count($errors) > 0)
        <span class="msg--flash">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </span>
    @endif
    <!-- <a href="{{ URL::to('/help') }}" class="help"><img src="{{asset('images/question.png')}}"
                                                       title="Klik for yderligere oplysninger og hjælp"
                                                       class="question"></a> -->
    @if(isset($hasSign) && $hasSign == 1)
        <p>Wign har allerede tegnet for <a href="{{ URL::to('/tegn/'.$word) }}">{{ $word }}</a>. Du kan enten tjekke om
            tegnet eksisterer, eller oprette et ekstra tegn for {{ $word }} nedunder:</p>
    @elseif(isset($word))
        <p>Wign har ikke tegnet{{ $word ? ' for "'.$word.'"' : ''}} endnu.<br>
            @else
                Du kan hjælpe os med at oprette et ny tegn nedenunder.</p>
    @endif
    <form method="POST" class="ligeform" id="opret_tegn" action="{{ URL::action('SignController@saveSign') }}">

        <camera id="wign01"
                data-app-id="{{ config('wign.cameratag.id') }}"
                data-maxlength="15"
                data-txt-message="Hej! Gå venligst til <<url>> for at optage din video"
                data-default-sms-country="{{ config('app.country_code') }}"
                style="width:580px;height:326px;"></camera>
        <br>

        <label for="word">Tegn for:</label>
        <input type="text" id="word" name="word" value="{{ isset($word) ? $word : "" }}" placeholder="Skriv ordet"><br>

        <label for="description">Beskrivelse:</label>
        <textarea id="description" name="description" placeholder="Skriv lidt om tegnet"></textarea><br>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" value="Indsend tegnet" id="btnSubmit">
        <p>
            <small>Ved indsendelsen bekræfter jeg at jeg har læst og accepteret Wign's <a
                        href="{{ URL::to('/retningslinjer/') }}">retningslinjer</a>
            </small>
        </p>

    </form>
@stop
