<div class="banner">
    @if (Request::path() !== '/')
        <a href="{{ URL::to('/') }}"><img src="{{asset('images/wign_logo.png')}}" alt="Wign logo" class="wign logo-banner"></a>
    @endif
    <ul class="menu">
        <li><a href="{{ URL::to('/opret') }}">Send et tegn</a></li>
        <li><a href="{{ URL::to('/requests') }}">Efterlyste tegn</a></li>
        <li><a href="{{ URL::to('/seneste') }}">Seneste tegn</a></li>
        <li><a href="{{ URL::to('/alle') }}">Alle tegn</a></li> {{-- Request fra Ragna. Bliver der indtil videre. Indtil der bliver til en alt for uoverskuelige liste --}}
        <li><a href="{{ URL::to('/om') }}">Om Wign</a></li>
        <li><span class="brand">v{{ $wignVersion }}</span></li>
    </ul>
</div>