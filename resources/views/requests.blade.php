@extends('layout.main')
<?php
if ( isset( $requests ) ) {
	if ( is_array( $requests ) ) {
		$count = count( $requests );
	} else {
		$count = 1;
	}
} else {
	$count = 0;
}
$title = trans_choice( 'text.requested.sign', $count );
?>
@section('title', $title)
@section('open_graph')
    @include('layout.openGraph', [
        'title' => $title,
        'url' => url( config('wign.urlPath.request') ),
        'desc' => __('text.text.request.desc')
    ])
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
    <ul>
        @foreach($requests as $request)
            <li><a href="{{ URL::to( config( 'wign.urlPath.sign' ) . '/' . Helper::makeUrlString( $request->word )) }}"
                   title="{{ $request->word }}">{{ $request->word }}</a>
                - {{ $request->requests_count }} {{ trans_choice('common.vote', $request->request_count) }}
            </li>
        @endforeach
    </ul>

@stop
