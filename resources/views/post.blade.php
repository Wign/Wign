<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 25/09/2018
 * Time: 13.29
 */
$title = $word;
$desc = __( 'text.wign.got.sign', [ 'word' => $word ] ) . ' ' . __( 'text.wign.journey' );
$url = isset( $hashtag ) ? url( config( 'wign.urlPath.tags' ) . '/' . substr( $word, 1 ) ) : url( config( 'wign.urlPath.sign' ) . '/' . $word );
$video = $signs[0]->video_uuid;
$video_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/mp4.mp4';
$image_url = 'https://www.cameratag.com/videos/' . $video . '/360p-16x9/thumb.png';
$image_width = '640';
$image_height = '360';
?>
@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layouts.openGraph', [
        'title' => $title,
        'url' => $url,
        'desc' => $desc,
        'video' => $video_url,
        'image' => $image_url,
        'width' => $image_width,
        'height' => $image_height
    ])
@stop

@section('sign')
    @include('layouts.cameratag')
    <div class="row">
        <div class="col-md-12">
            <p class="quote"> {{ $post->word->word }} </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{--<input type="submit" value="Ret" id="btnEdit" style="float:right">--}}
            <a href="{{ route('post.edit', ['id' => $post->id]) }}"> </a>
            <p> {{ $post->video }} </p>
            <p> {{ $post->description }} </p>
            <p> {{ count($post->likes) }} bruger tegnet | <a href="{{ route('post.like', ['id' => $post->id]) }}">Det gør jeg også! </a></p>
        </div>
    </div>
@endsection