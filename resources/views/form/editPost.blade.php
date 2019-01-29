<?php
$title = $word->word;
$url = isset( $hashtag ) ? url( config( 'wign.urlPath.tags' ) . '/' . substr( $word, 1 ) ) : url( config( 'wign.urlPath.sign' ) . '/' . $word );
$video_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/mp4.mp4';
$image_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/thumb.png';
$image_width = '640';
$image_height = '360';
?>
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'video' => $video_url, 'image' => $image_url, 'width' => $image_width, 'height' => $image_height])
@stop

@section('extra_head_scripts')
    @include('layout.cameratag')
    <script>
        $(document)
            .one('focus.autoExpand', 'textarea.autoExpand', function(){
                var savedValue = this.value;
                this.value = '';
                this.baseScrollHeight = this.scrollHeight;
                this.value = savedValue;
            })
            .on('input.autoExpand', 'textarea.autoExpand', function(){
                var minRows = this.getAttribute('data-min-rows')|0, rows;
                this.rows = minRows;
                rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
                this.rows = minRows + rows;
            });

        function switchWindow() {
            const videoPlayer = document.getElementById("videoPlayer");
            const videoEdit = document.getElementById("videoEdit");

            if(videoPlayer.style.display === "none") {
                videoPlayer.style.display = "block";
                videoEdit.style.display = "none";
            }
            else {
                videoPlayer.style.display = "none";
                videoEdit.style.display = "block";
            }
        };
    </script>
@stop

@section('content')
    {{$DEBUG = config('global.debug')}}
    @if($DEBUG)
        DEBUG: <br>
        ID: {{$post->id}} <br>
        W: {{$word->word}} <br>
        V: {{$video->video_uuid}} <br>
        D: {{$desc->text}} <br>
        IL: {{$post->rank}} <br>
        QCV: {{Auth::user()->rank()}} <br>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form method="POST" class="ligeform" id="opret_tegn" action="{{ route('post.edit.save', ['id' => $post->id])}}">
        <label for="word">{{ __( 'text.edit.word' ) }}</label>
        <input type="text" id="word" name="word" value="{{ old('word') ?? $word->word }}"
               placeholder="{{__('text.form.word.ph')}}">
        <label for="switchButton">{{ __( 'text.edit.video' ) }}</label>
        <button id="switchButton" type="button" class="btn" onclick="switchWindow()">    {{--TODO: Få det til at virke--}}
            {{__('text.edit.video.new')}}
        </button>
            <div id="videoPlayer">
                <player id="video_{{ $video->id }}"
                        data-uuid="{{ $video->video_uuid }}"
                        data-controls="true"
                        data-displaytitle="false"
                        data-displaydescription="false"
                        data-mute="true">
                </player>
                <br>
            </div>
            <div id="videoEdit" style="display:none;">
                {{ csrf_field() }}
                @if( empty( old('wign01_uuid') ) )
                    <camera id="wign01"
                            data-app-id="{{ config('wign.cameratag.id') }}"
                            data-maxlength="15"
                            data-txt-message="{{ __( 'text.create.sms.url' ) }}"
                            data-default-sms-country="{{ config('app.country_code') }}"
                            style="width:580px;height:326px;"></camera>
                @else
                    <input type="hidden" name="wign01_uuid" value="{{ old('wign01_uuid') }}">
                    <input type="hidden" name="wign01_vga_mp4" value="{{ old('wign01_vga_mp4') }}">
                    <input type="hidden" name="wign01_vga_thumb" value="{{ old('wign01_vga_thumb') }}">
                    <input type="hidden" name="wign01_qvga_thumb" value="{{ old('wign01_qvga_thumb') }}">
                    <player id="video01"
                            data-uuid="{{ old('wign01_uuid') }}"
                            data-controls="true"
                            data-displaytitle="false"
                            data-displaydescription="false"
                            data-mute="true"></player>
                @endif
            </div>
        <br>
        <label for="description">{{__('text.form.desc')}}</label>
        <textarea class='autoExpand' name="description" rows='3' data-min-rows='3' placeholder="{{__('text.form.desc.ph')}}">
            {{ old('desc') ?? $desc->text }}
        </textarea>
        <br>
        <label for="il">{{__('text.edit.il')}}</label>
        <select class="btn btn-secondary" id="il" name="il">
            @for( $i = 1; $i <= Auth::user()->rank(); $i++)
                @if( $post->rank == $i) {{-- or: Auth::user()->rank() == $i || (Auth::user()->rank() == 0 && $i == 1) --}}
                    <option selected value="{{$i}}">{{$i}}</option>
                @else
                    <option value="{{$i}}">{{$i}}</option>
                @endif
            @endfor
        </select>
        <small>Nuværende: {{$post->rank}}</small>
        <p>
            <input type="submit" value="{{__('text.edit.submit')}}">
            <small>@lang('text.submit.accept.terms', ['url' => URL::to( config( 'wign.urlPath.policy' ))])</small>
        </p>
    </form>
@endsection