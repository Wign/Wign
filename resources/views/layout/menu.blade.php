<div class="banner">
    @if (Request::path() !== '/')
        <a href="{{ URL::to('/') }}"><img src="{{ asset( 'images/wign_logo_new.png' ) }}" alt="{{ __( 'common.Wign.logo' ) }}" class="wign logo-banner"></a>
    @endif
    <ul class="menu">
        @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('common.login') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('common.signin') }}</a>
            </li>
        @else
            <li><a href="{{ route('post.new') }}">{{ __( 'common.submit.sign' ) }}</a></li>
            <li><a href="{{ route('request.index') }}">{{ __( 'common.requested.sign' ) }}</a></li>
            <li><a href="{{ route('post.recent') }}">{{ __( 'common.latest.sign' ) }}</a></li>
            <li><a href="{{ URL::to( config('wign.urlPath.all')) }}">{{ __( 'common.all.sign' ) }}</a></li>
            <li><a href="{{ route('user.index') }}">{{ __( 'common.user.profile' ) }}</a></li>
            <li class="nav-item dropdown">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                        {{ __('common.logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
                {{--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a> --}}
            </li>
            @if (Auth::user()->type == 'admin')
                <li><a style="color:#AFA" href="{{ route('admin.index') }}">{{ __( 'common.menu.admin' ) }}</a></li>
            @endif
        @endguest
        <li><a href="{{ URL::to( config('wign.urlPath.about')) }}">{{ __( 'common.Wign.About' ) }}</a></li>
        <li><span class="text">{{ config( 'wign.version' ) }}</span></li>
    </ul>
</div>