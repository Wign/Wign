<?php
    if(isset($word)) {
        $title = 'Opret et tegn for '.$word;
        $desc = 'Hjælp os med din bidrag til '.$word.'. Send dit tegn for '.$word.' ind ved at bruge din mobil eller computer.';
        $url = url('/opret/'.$word);
    }
    else {
        $title = 'Opret et tegn';
        $desc = 'Hjælp os med din bidrag. Send dit tegn ind ved at bruge din mobil eller computer.';
        $url = url('/opret');
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
    CameraTag.observe('wign01', 'recordingStarted', function(){
        // Disable the submit button (until the video is published)
        $('#btnSubmit').attr('disabled', true);
        
        // Set a alert on before unload
        $(window).bind('beforeunload', function(){
            return 'Pas på! Din video er ikke blevet lagt op endnu!\nHusk at klikke på "Indsend tegnet". Tak!';
        });
    });
    
    /**
     * Observe the camera
     * @param  {published}
     * @return {enable button}     On publish, enable the submit button again!
     */
    CameraTag.observe('wign01', 'published', function(){
        $( '#btnSubmit' ).prop('disabled', false);
    });

    $(document).ready(function() {
        // Unbinding the beforeunload event fired before if clicked on the submit button
        $('form').submit(function(){
            $(window).unbind("beforeunload");
        });

        // Add tooltip UI to the document
        $( document ).tooltip({
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
    </div>
    @endif 
<a href="{{ URL::to('/help') }}" class="help"><img src="{{asset('images/question.png')}}" title="Klik for yderligere oplysninger og hjælp" class="question"></a>
<?php 
    if($hasSign) {
?>
    <p>Wign har allerede tegnet for <a href="{{ URL::to('/tegn/'.$word) }}">{{ $word }}</a>. Du kan enten tjekke om tegnet eksisterer, eller oprette et ekstra tegn for {{ $word }} nedunder:</p>
<?php
    }
    else {
        if($word) {
?>
    <p>Wign har ikke tegnet{{ isset($word) ? ' for "'.$word.'"' : ''}} endnu.<br>
        <?php } ?>
    Du kan hjælpe os med at oprette et ny tegn nedenunder.</p>
<?php
}
?>
    <form method="POST" class="ligeform" id="opret_tegn" action="{{ URL::action('TegnController@gemTegn') }}">
        
        <camera id="wign01" data-app-id="{{ $appID }}" data-maxlength="15" data-txt-message="Hej. Vær sød at gå til <<url>> for at optage din video" style="width:580px;height:326px;" data-cssurl="{{asset('css/cameraTag.css')}}"></camera><br>

        <label for="tegn">Tegn for:</label>
        <input type="text" id="tegn" name="tegn" value="{{ $word }}" placeholder="Skriv ordet"><br>
        
        <label for="beskrivelse">Beskrivelse:</label>
        <textarea id="beskrivelse" name="beskr" placeholder="Skriv lidt om tegnet"></textarea><br>
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" value="Indsend tegnet" id="btnSubmit">
        <p><small>Ved indsendelsen bekræfter jeg at jeg har læst og accepteret Wign's <a href="{{ URL::to('/retningslinjer/') }}">retningslinjer</a><small></p>

    </form>
@stop