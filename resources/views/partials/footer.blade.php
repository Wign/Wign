<div class="footer">
    @lang('text.numSign', ['numSigns' => $signCount])
    @lang('text.numWords', ['numWords' => $wordCount, 'url' => URL::to(config('wign.urlPath.all'))])
    <br>&copy; 2009-{{ date('Y') }}
    <a href="{{ URL::to( config('wign.urlPath.about' )) }}" title="{{ __( 'common.Wign.About' ) }}">Wign</a> | <a
            href="{{ config( 'social.facebook.url' ) }}" title="{{ __( 'text.urlTitle.facebook' ) }}">Facebook</a>
</div>