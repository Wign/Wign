<div class="footer">
    Wign har nu {{ $signCount }} tegn, fordelt over <a href="{{ URL::to('/alle') }}" title="oversigt over alle tegne">{{ $wordCount }}</a> ord<br>
    &copy; 2009-{{ date('Y') }} <a href="{{ URL::to('/om') }}" title="Om Wign">Wign</a> | <a href="{{ config('social.facebook.url') }}" title="besøg vores facebook side">Facebook</a>
</div>