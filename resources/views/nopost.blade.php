<?php
if ( isset( $word ) ) {
	$title = __('text.sign.need.word', ['word' => $word]);
	$desc  = __('text.sign.not.have.word', ['word' => $word]) . ' ' . __('text.sign.contribute.word', ['word' => $word]);
	$url   = url( config('wign.urlPath.sign') . '/' . $word );
} else {
	$title = __('text.sign.need');
	$desc  = __('text.sign.not.have') . ' ' . __('text.sign.contribute');
	$url   = url( config('wign.urlPath.sign') );
}
?>

@extends('layout.main')

@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', ['title' => $title, 'url' => $url, 'desc' => $desc])
@stop

@section('content')
    <h1>{{ $title }}</h1>
        @if (isset($word) && !Auth::user()->isEntry())
            <p>@lang('text.sign.not.have.word', ['word' => $word])</p>
            @lang('text.sign.either')
            <ul>
                <li><a href="{{ URL::to( config('wign.urlPath.create') . '/' . $word) }}"
                       title="{{__('text.create.sign.word', ['word' => $word])}}">@lang('text.create.sign.word', ['word' => $word])</a></li>
                @lang('text.or')
                <li><a href="{{ URL::to( config('wign.urlPath.createRequest') . '/' . $word) }}">@lang('text.sign.request.word', ['word' => $word])</a>
                </li>
            </ul>
        @else
            <p>@lang('text.sign.not.have')</p>
            <p><a href="{{ URL::to( config('wign.urlPath.create') ) }}" title="{{__('text.create.sign')}}">@lang('text.create.sign')</a></p>
        @endif

        @if(isset($suggestions))
            <p>
            <h3>@lang('text.sign.alternates')</h3>
            <ul>
                @foreach($suggestions as $suggest)
                    <li><a href="{{ URL::to( config('wign.urlPath.sign') . '/' . $suggest) }}">{{ $suggest }}</a></li>
                @endforeach
            </ul>
            </p>
        @endif
@stop