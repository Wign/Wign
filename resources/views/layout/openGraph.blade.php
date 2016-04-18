<link rel="canonical" href="{{$url or url('/')}}" />

<meta property="og:site_name" content="Wign" />
<meta property="og:title" content="Wign :: {{$title or 'Social tegnsprogsencyklopædi'}}" />
@if (isset($desc))
<meta property="og:description" content="{{$desc}}" />
@endif
<meta property="og:url" content="{{$url or url('/')}}" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="da_DK" />

<meta property="og:image" content="{{$image or asset('images/wign-fb_image.png')}}" />
<meta property="og:image:type" content="{{$image_type or 'image/png'}}" />
@if (isset($width))
<meta property="og:image:width" content="{{$width}}" />
@endif
@if (isset($height))
<meta property="og:image:height" content="{{$height}}" />
@endif


@if (isset($video))
<meta property="og:video" content="{{$video}}" />
<meta property="og:video:secure_url" content="{{$video}}" />
<meta property="og:video:type" content="video/mp4" />
<meta property="og:video:width" content="640" />
<meta property="og:video:height" content="360" />
@endif