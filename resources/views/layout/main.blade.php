<?php
    $ordet = $word->word ?? $word ?? null;
?>
<!DOCTYPE html>
<html lang="da">
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
    </head>
    <body>
        @include('layout.menu')

        <div class="wrapper">
            @yield('content')
        </div>

        @include('cookieConsent::index')
    </body>
</html>