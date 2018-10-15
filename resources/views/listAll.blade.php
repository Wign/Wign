@extends('layout.main')
@section('title', __('text.signs.all'))
@section('open_graph')
    @include('layout.openGraph', [
        'title' => __('text.signs.all'),
        'url' => url( config( 'wign.urlPath.all' ) ),
        'desc' => __('text.signs.all.desc')
    ])
@stop

@section('content')
<h1>@lang('text.signs.all')</h1>

    <ol>
    @foreach($words as $word)
        <li><a href="{{{ URL::to( config( 'wign.urlPath.sign' ) ).'/'.Helper::makeUrlString($word->word) }}}">{{{ $word->word }}}</a> ({{{ $word->posts_count }}})</li>
    @endforeach
    </ol>

@stop