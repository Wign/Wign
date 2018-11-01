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
    <form method="POST" class="ligeform" id="opret_tegn" action="{{ route('post.create') }}">
        <label for="word">{{ __( 'text.edit.word' ) }}</label>
        <input type="text" id="word" name="word" value="{{ old('word') ?? $word->word }}"
               placeholder="{{__('text.form.word.ph')}}"><br>
        <br>
        @if( true ) {{-- Show current video TODO: Lav script til det--}}
            <label for="video">{{ __( 'text.edit.video' ) }}</label>
            <player id="video_{{ $video->id }}"
                    data-uuid="{{ $video->video_uuid }}"
                    data-controls="true"
                    data-displaytitle="false"
                    data-displaydescription="false"
                    data-mute="true">
            </player>
            <button id="btnEdit" class="btn" onclick="location.href='{{ back() }}'">
                {{__('text.edit.video.new')}}
            </button>
            <br>
        @else   {{-- If want new video --}}
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
        @endif
        <br>
        <label for="description">{{__('text.form.desc')}}</label>
        <textarea class='autoExpand' rows='3' data-min-rows='3' placeholder="{{__('text.form.desc.ph')}}">
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

        <p>
            <button id="btnEdit" class="btn" onclick="location.href='{{ back() }}'">
                {{__('text.edit.submit')}}
            </button>
            <small>@lang('text.submit.accept.terms', ['url' => URL::to( config( 'wign.urlPath.policy' ))])</small>
        </p>
    </form>
@endsection