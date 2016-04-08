<?php
    if($word) { $titel = ucfirst($word->word); }
?>

@extends('layout.main')

@section('title')
{{ $titel }}
@stop

@section('extra_head_scripts')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var addUrl = "{{ URL::to('/createVote') }}";
    var delUrl = "{{ URL::to('/deleteVote') }}";

    $(function() {
        $( document ).tooltip({
            position: {my: "center bottom", at: "center top-5", collision: "none"}
        });
    });
</script>
    @include('layout.cameratag')

@stop

@section('content')
<h1>{{ $titel }}</h1>
@if(Session::has('message'))
    @if(Session::has('url'))
        <a href="{{ Session::get('url') }}">
    @endif
    <span class="msg--flash">{{ Session::get('message') }}</span>
    @if(Session::has('url'))
        </a>
    @endif
@endif

{{-- <p>{{ $word->description }}</p> // Ordet har ikke beskrivelse. Kun hver tegn! --}}

<div id="signs">
<?php $myIP = Request::getClientIp(); ?>
@foreach($signs as $sign)
<?php 
    $IPs = explode(",", $sign->votesIP);
    $hasVote = in_array($myIP, $IPs);
?>
    <div class="sign" data-count="{{ $sign->sign_count }}" data-id="{{$sign->id}}">
        <video id="video_{{ $sign->id }}" data-uuid="{{ $sign->video_uuid }}" data-options='{"mute":true, "controls":true}'></video>
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
<a href="{{ URL::to('/opret/'.$word->word) }}" class="float--right" alt="Lav en ny forslag">Forslå et alternativt tegn</a>
</div>

@stop