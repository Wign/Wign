<div class="banner">
    @if (Request::path() !== '/')
        <a href="{{ URL::to('/') }}"><img src="{{ asset( 'images/wign_logo_new.png' ) }}" alt="{{ __( 'common.Wign.logo' ) }}" class="wign logo-banner"></a>
    @endif
    <ul class="menu">
        @if(Auth::check())
        <li><a href="{{ URL::to( config('wign.urlPath.create')) }}">{{ __( 'common.submit.sign' ) }}</a></li>
        <li><a href="{{ URL::to( config('wign.urlPath.request')) }}">{{ __( 'common.requested.sign' ) }}</a></li>
        <li><a href="{{ URL::to( config('wign.urlPath.recent')) }}">{{ __( 'common.latest.sign' ) }}</a></li>
        <li><a href="{{ URL::to( config('wign.urlPath.all')) }}">{{ __( 'common.all.sign' ) }}</a></li> {{-- Request fra Ragna. Bliver der indtil videre. Indtil der bliver til en alt for uoverskuelige liste --}}
        <li class="nav-item dropdown">
            {{--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>--}}
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        </li>
        @endif
        <li><a href="{{ URL::to( config('wign.urlPath.about')) }}">{{ __( 'common.Wign.About' ) }}</a></li>
        <li><span class="brand">{{ config( 'wign.version' ) }}</span></li>
    </ul>
</div>