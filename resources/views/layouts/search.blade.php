@if(Auth::check())
<form id="indexSearch" method="POST" action="{{{ URL::action('SearchController@redirect') }}}" class="search-form">
    <div class="input-wrapper">
        <input type="text" id="autoComplete" class="search-text" name="word" placeholder="{{ __('text.search.random', ['word' => $randomWord['word']]) }}">
        <input type="submit" class="search-submit" value="{{ __('common.Search') }}" id="search-submit">
    </div>
    {{ csrf_field() }}
</form>
@endif