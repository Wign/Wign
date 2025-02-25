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
    <h3 class="headline" style="background-color: #FF0000; color: white; font-weight: bold;">WIGN.dk har tekniske udfordringer som vi arbejder på at få løst hurtigst. Vi beklager nedetiden.</h3>
    @include('layout.search', ['randomWord' => $randomWord])
    </div>

    @include('layout.footer',['signCount' => $signCount])

@stop