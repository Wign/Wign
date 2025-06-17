@extends('layout.main')

@section('open_graph')
    @include('layout.openGraph', [
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
<link rel="stylesheet" href="/css/logo-row.css">
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
        @include('layout.search', ['randomWord' => $randomWord])
    </div>
    @include('layout.footer',['signCount' => $signCount])
    <div class="logo-row">
        <a href="https://mallard.dk" target="_blank" rel="noopener">
            <img src="{{asset('images/mallard-logo.png')}}" alt="Mallard logo" class="partner-logo">
        </a>
        <a href="https://tegnsprogstolken.dk" target="_blank" rel="noopener">
            <img src="{{asset('images/tegnsprogstolkendk-logo.png')}}" alt="Tegnsprogstolken.dk logo" class="partner-logo">
        </a>
        <a href="https://teto.nu" target="_blank" rel="noopener">
            <img src="{{asset('images/tetonu-logo.png')}}" alt="TETO.nu logo" class="partner-logo">
        </a>
    </div>

@stop
