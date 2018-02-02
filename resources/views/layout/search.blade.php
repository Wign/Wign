<form id="indexSearch" method="POST" action="{{{ URL::action('SearchController@redirect') }}}" class="search-form">
    <div class="input-wrapper">
        <input type="text" id="autoComplete" class="search-text" name="word" placeholder="{{ __('text.search.random', ['word' => $randomWord['word']]) }}">
    </div>
    {{ csrf_field() }}
    <input type="submit" class="search-submit" value="{{ __('common.Search') }}" id="search-submit">
</form>