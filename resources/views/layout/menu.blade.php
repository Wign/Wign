<div class="banner">
    @if (Request::path() !== '/')
        <a href="{{ URL::to('/') }}"><img src="{{ asset( 'images/wign_logo_new.png' ) }}" alt="{{ __( 'common.Wign.logo' ) }}" class="wign logo-banner"></a>
    @endif
    <ul class="menu">
        <li><a href="{{ route('post.new') }}">{{ __( 'common.submit.sign' ) }}</a></li>
        <li><a href="{{ route('request.index') }}">{{ __( 'common.requested.sign' ) }}</a></li>
        <li><a href="{{ route('post.recent') }}">{{ __( 'common.latest.sign' ) }}</a></li>
        <li><a href="{{ URL::to( config('wign.urlPath.all')) }}">{{ __( 'common.all.sign' ) }}</a></li> {{-- Request fra Ragna. Bliver der indtil videre. Indtil der bliver til en alt for uoverskuelige liste --}}
        <li><a href="{{ URL::to( config('wign.urlPath.about')) }}">{{ __( 'common.Wign.About' ) }}</a></li>
        <li><span class="brand">{{ config( 'wign.version' ) }}</span></li>
    </ul>
</div>