<?php
    $title = $word->word;
    $desc = 'Wign har tegnet for '.$word->word.'. Kom og prøv at tjekke om vi har andre tegn for '.$word->word.'. Tag en rejse i vores udvalg af tegn, og bliv inspireret.';
    $url = url('/tegn/'.$word->word);
    $video = $signs[0]->uuid;
    $video_url = 'https://www.cameratag.com/videos/'.$video.'/360p-16x9/mp4.mp4';
    $image_url = 'https://www.cameratag.com/videos/'.$video.'/360p-16x9/thumb.png';
    $image_width = '640';
    $image_height = '360';
?>
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc, 'video' => $video_url, 'image' => $image_url, 'width' => $image_width, 'height' => $image_height])
@stop

@section('extra_head_scripts')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var addUrl = "{{ URL::to('/createVote') }}"; // Used in another script file
    var delUrl = "{{ URL::to('/deleteVote') }}"; // Used in another script file

    $(function() {
        $( document ).tooltip({
            position: {my: "center bottom", at: "center top-5", collision: "none"}
        });
    });
</script>
    @include('layout.cameratag')

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

<div id="signs">
<?php $myIP = Request::getClientIp(); ?>
@foreach($signs as $sign)
<?php 
    $IPs = explode(",", $sign->votesIP);
    $hasVote = in_array($myIP, $IPs);
?>
    <div class="sign" data-count="{{ $sign->sign_count }}" data-id="{{$sign->id}}">
        <video id="video_{{ $sign->id }}" data-uuid="{{ $sign->uuid }}" data-options='{"mute":true, "controls":true}'></video>
        <span class="count">{{ $sign->sign_count }}</span>
        @if($hasVote)
            <a href="#" class="delVote" title="Jeg bruger ikke det tegn">&nbsp;</a>
        @else
            <a href="#" class="addVote" title="Jeg bruger dette tegn">&nbsp;</a>
        @endif
        <a href="{{ URL::to('/flagSignView')."/".$sign->id }}" class="flagSign" title="Rapportér tegnet"><img src="{{ asset('images/flag-black.png') }}" class="anmeld"></a>
        <div class="desc">{{$sign->description}}</div>
    </div>
@endforeach
</div>
<a href="{{ URL::to('/opret/'.$word->word) }}" class="float--right" title="Lav en ny forslag">Forslå et alternativt tegn</a>

@stop