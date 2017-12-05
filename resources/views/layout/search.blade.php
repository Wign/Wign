<form id="indexSearch" method="POST" action="{{{ URL::action('SearchController@redirect') }}}" class="search-form">
    <div class="input-wrapper">
        <input type="text" id="autoComplete" class="search-text" name="word" placeholder="Søg efter et tegn, fx. {{ $randomWord['word'] }}">
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="search-submit" value="Søg" id="search-submit">
</form>