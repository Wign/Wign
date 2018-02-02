@extends('layout.main')

@section('title', 'Hjælp')
@section('open_graph')
    @include('layout.openGraph', [
        'title' => __('common.Help'),
        'url' => url( config( 'wign.urlPath.help' ) ),
        'desc' => __('text.help.desc')
    ])
@stop

@section('content')

    <h1>@lang('common.Help')</h1>
    <p>@lang('text.help.line1')<br>@lang('text.help.line2  ')</p>
    <p>@lang('text.help.line3', ['gitUrl' => config('social.github.url').'/issues', 'mail' => 'mailto:'.config('wign.email')])</p>
    <p>@lang('text.help.line4', ['fbUrl' => config('social.facebook.url')])</p>
    <p>@lang('common.Thank.patience')</p>

@stop