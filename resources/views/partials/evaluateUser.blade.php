{{$DEBUG = config('global.debug')}}
@if($DEBUG)
    DEBUG: <br>
    ID: {{$remotion->id}} <br>
@endif

@if($remotion->promotion)
    <h1>Promote '{{$user->name}}' ?</h1>
@else
    <h1>Demote '{{$user->name}}' ?</h1>
@endif
<p>Brugeren blev oprettet d. {{$user->created_at}}.</p>
<p>Brugeren har bidraget med {{$user->post_count}} optagelse</p>

<br>