<?php
    if(isset($word->word)) { $ordet = $word->word; }
    elseif(isset($word)) { $ordet = $word; }
    else { $ordet = null; }
?>
<!DOCTYPE html>
<html>
    <head prefix="og: http://ogp.me/ns#">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=0.6"> 
    
        <title>Wign :: @yield('title', 'Social tegnsprogsencyklopædi')</title>

        @section('open_graph')
            @include('layout.openGraph')
        @show

        @include('layout.icons')
        @include('layout.style')
    
        {{-- <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-75868038-1', 'auto');
            ga('send', 'pageview');
        </script> --}}
    </head>
    <body>

        @include('layout.menu')
        
        <div class="wrapper">
        
            @yield('content')

        </div>
        @include('cookieConsent::index')
    </body>
</html>