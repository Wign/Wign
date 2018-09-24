@extends('layouts.main')

@section('open_graph')
    @include('layouts.openGraph', [
        'desc' => __('text.index.desc')
    ])
@stop

@section('extra_head_scripts')
<script>
$(function() {
    $( "#autoComplete" ).autocomplete({
        source: "{{ URL::to('autocomplete') }}",
        minLength: 2,
        delay: 0,
        autoFocus: true,
        select: function(key, value) {
            if(value.item.dtype === "tag") {
                window.location.href = value.item.url;
            }
            else {
                window.location.href = value.item.url;
            }
        }
    });
});
</script>
@stop

@section('content')

    <div class="buffer">
    @if(Session::has('message'))
        @if(Session::has('url'))
            <a href="{{ Session::get('url') }}">
        @endif
        <span class="msg--flash">{{ Session::get('message') }}</span>
        @if(Session::has('url'))
            </a>
        @endif
    @endif

    <img src="{{asset('images/wign_logo_new.png')}}" alt="{{__('common.Wign.logo')}}" class="wign logo-index">
    <h1 class="headline">@lang('common.wign.jargon')</h1>
    @include('layouts.search', ['randomWord' => $randomWord])
    </div>

    @include('partials.footer',['signCount' => $signCount])

@stop