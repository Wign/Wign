@extends('layout.main')

@section('title', __('text.recent.count', ['count' => $number]))
@section('open_graph')
    @include('layout.openGraph', [
        'title' =>  __('text.recent.count', ['count' => $number]),
        'url' => url( config('wign.urlPath.recent') ),
        'desc' => __('text.text.recent.desc', ['count' => $number])
    ])
@stop

@section('content')
    <h1>@lang('text.recent.count', ['count' => $number])</h1>

    <ul>
        @foreach($words as $word)
            <li>
                <a href="{{ URL::to('sign', ['word' => Helper::makeUrlString($word->word)]) }}">{{ $word->word }}</a>
            </li>
        @endforeach
    </ul>
    <a href="{{ URL::to( config('wign.urlPath.all')) }}" class="float--right" title="{{__('text.signs.all.our')}}">@lang('text.signs.all.look')</a>

@stop
