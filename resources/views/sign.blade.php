<?php
$title = $word;
$desc = __( 'text.wign.got.sign', [ 'word' => $word ] ) . ' ' . __( 'text.wign.journey' );
$url = $hashtag ? url( config( 'wign.urlPath.tags' ) . '/' . substr( $word, 1 ) ) : url( config( 'wign.urlPath.sign' ) . '/' . $word );
$video = $signs[0]->video_uuid;
$video_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/mp4.mp4';
$image_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/thumb.png';
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

        $(function () {
            $(document).tooltip({
                position: {my: "center bottom", at: "center top-5", collision: "none"}
            });
        });
    </script>
    @include('layout.cameratag')

@stop

@section('content')
    <h1>{{ $title }}</h1>
    @isset($hashtag)
        <p>@lang('text.hash.count.signs', ['count' => $signs->count()])</p>
    @endisset
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
			if ( $sign->isTagged == true ) {
				$description = \App\Services\TagService::replaceTagsToURL( $sign->description );
			} else {
				$description = $sign->description;
			}
			?>


            <div class="sign" data-count="{{ $sign->sign_count }}" data-id="{{$sign->id}}">
                @isset($hashtag)
                    <h2>{{ $sign->word->word }}</h2>
                @endisset
                <player id="video_{{ $sign->id }}"
                        data-uuid="{{ $sign->video_uuid }}"
                        data-controls="true"
                        data-displaytitle="false"
                        data-displaydescription="false"
                        data-mute="true"></player>
                <span class="count">{{ $sign->sign_count }}</span>
                @if(isset($sign->voted))
                    <a href="#" class="delVote" title="{{__('text.I.use.sign.not')}}">&nbsp;</a>
                @else
                    <a href="#" class="addVote" title="{{__('text.I.use.sign')}}">&nbsp;</a>
                @endif
                <a href="{{ URL::to('/flagSignView')."/".$sign->id }}" class="flagSign"
                   title="{{__('text.sign.report')}}"><img src="{{ asset('images/flag-black.png') }}"
                                                           class="anmeld"></a>
                <div class="desc">{!! $description !!}</div>
            </div>
        @endforeach
    </div>
    @empty($hashtag)
        <a href="{{ URL::to( config('wign.urlPath.create'). '/' . $word) }}" class="float--right"
           title="{{__('text.sign.suggest.word', ['word' => $word])}}">@lang('text.sign.alt.suggest')</a>
    @endempty

@stop
