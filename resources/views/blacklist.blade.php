@extends('layout.main')

@section('title', __( 'text.blacklisted' ) )

@section('content')

    <div class="buffer">

        <img src="{{asset('images/wign_logo.png')}}" alt="{{ __('common.Wign.logo') }}" class="wign logo-index" width="269" height="85">
        <h1 class="headline">{{ __('text.sorry') }}</h1>

        <p>@lang( 'text.blacklist.ipFound', ['IP' => Session::get('ip'), 'reason' => Session::get('reason') ] )</p>
        <p>@lang( 'text.blacklist.notAccept' )</p>
        <p>@lang( 'text.blacklist.contact', ['mail' => 'mailto:' . config('wign.email') ] )</p>
    </div>

@stop
