<?php
if ( isset( $word ) ) {
	$title = __( 'text.create.sign.word', [ 'word' => $word ] );
	$desc  = __( 'text.create.send.word', [ 'word' => $word ] );
	$url   = url( config( 'wign.urlPath.create' ) . '/' . $word );
} else {
	$title = __( 'text.create.sign' );
	$desc  = __( 'text.create.send' );
	$url   = url( config( 'wign.urlPath.create' ) );
}
?>
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc])
@stop

@section('extra_head_scripts')
    @include('lang.cameratag')
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
                return '@lang( 'text.create.beware' )';
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

    @if(isset($hasSign) && $hasSign == 1)
        <p>@lang('text.create.already.exist', ['word' => $word, 'url' => URL::to( config( 'wign.urlPath.sign' ) . '/' . $word )] )</p>
    @elseif(isset($word))
        <p>@lang('text.create.nonexistent.word', ['word' => $word] ) @lang('text.create.help.us')<p>
    @else
        <p>@lang('text.create.nonexistent') @lang('text.create.help.us')</p>
    @endif
    <form method="POST" class="ligeform" id="opret_tegn" action="{{ URL::action('SignController@saveSign') }}">

        <camera id="wign01"
                data-app-id="{{ config('wign.cameratag.id') }}"
                data-maxlength="15"
                data-txt-message="{{ __( 'text.create.sms.url' ) }}"
                data-default-sms-country="{{ config('app.country_code') }}"
                style="width:580px;height:326px;"></camera>
        <br>

        <label for="word">{{ __( 'text.form.word' ) }}</label>
        <input type="text" id="word" name="word" value="{{ isset($word) ? $word : "" }}" placeholder="{{__('text.form.word.ph')}}"><br>

        <label for="description">{{__('text.form.desc')}}</label>
        <textarea id="description" name="description" placeholder="{{__('text.form.desc.ph')}}"></textarea><br>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" value="{{__('text.form.submit.sign')}}" id="btnSubmit">
        <p>
            <small>@lang('text.submit.accept.terms', ['url' => URL::to( config( 'wign.urlPath.policy' ))])</small>
        </p>

    </form>
@stop
