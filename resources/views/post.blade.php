<?php
$title = $word;
$desc = __( 'text.wign.got.sign', [ 'word' => $word ] ) . ' ' . __( 'text.wign.journey' );
$url = isset( $hashtag ) ? url( config( 'wign.urlPath.tags' ) . '/' . substr( $word, 1 ) ) : url( config( 'wign.urlPath.sign' ) . '/' . $word );
$video = $posts[0]->video_uuid;
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
    <div id="post">
		<?php $user = Auth::user(); ?>
        @foreach($posts as $post)
			<?php
                $video = $post->currentVideo();
			?>
                <div class="post" data-count="{{ $post->likes_count }}" data-id="{{$post->id}}">
                    <button id="btnEdit" class="btn" onclick="location.href='{{ route('post.edit') }}'">
                        Ret
                    </button>
                @isset($hashtag)
                    <h2>{{ $post->theWord }}</h2>
                @endisset
                <player id="video_{{ $video->id }}"
                        data-uuid="{{ $video->video_uuid }}"
                        data-controls="true"
                        data-displaytitle="false"
                        data-displaydescription="false"
                        data-mute="true"></player>
                <span class="count">{{ $post->likes_count }}</span>
                @if( $post->liked )
                    <a href="#" class="delVote" title="{{__('text.I.use.sign.not')}}">&nbsp;</a>
                @else
                    <a href="#" class="addVote" title="{{__('text.I.use.sign')}}">&nbsp;</a>
                @endif
                <div class="desc">{!! nl2br($post->descText) !!}</div>
            </div>
        @endforeach
    </div>
    @empty($hashtag)
        <a href="{{ URL::to( config('wign.urlPath.create'). '/' .  Helper::makeUrlString( $word ) ) }}"
           class="float--right"
           title="{{__('text.sign.suggest.word', ['word' => $word])}}">@lang('text.sign.alt.suggest')</a>
    @endempty

@stop
