{{$DEBUG = config('global.debug')}}
@if($DEBUG)
    DEBUG: <br>
    ID: {{$review->id}} <br>
@endif

<h1>Review the post</h1>
@if($oldPost !== null)
<?php
$video = $oldPost->video;
?>
<label for="new"><b>Tidligere</b></label>
<div class="post" name="new">
    @if($oldPost->word != $newPost->word)
        <label for="word">{{__('text.form.word')}}</label>
        <h3 id="word">{{$oldPost->word->word}}</h3>
    @else
        <h3 id="word">- <i>{{__('text.review.word.unchange')}}</i> -</h3>
    @endif
    @if($oldPost->video != $newPost->video)
        <player id="video_{{ $video->id }}"
                data-uuid="{{ $video->video_uuid }}"
                data-controls="true"
                data-displaytitle="false"
                data-displaydescription="false"
                data-mute="true">
        </player>
    @else
        <h3>- <i>{{__('text.review.video.unchange')}}</i> -</h3>
    @endif
    @if($oldPost->description != $newPost->description)
        <div class="desc">{!! nl2br($oldPost->description->text) !!}</div>
    @else
        <h3>- <i>{{__('text.review.desc.unchange')}}</i> -</h3>
    @endif
</div>
@endif
<br>
<?php
$video = $newPost->video;
?>
<div class="post">
    <label for="word">{{__('text.form.word')}}</label>
    <h3 id="word">{{$newPost->word->word}}</h3>

    <player id="video_{{ $video->id }}"
            data-uuid="{{ $video->video_uuid }}"
            data-controls="true"
            data-displaytitle="false"
            data-displaydescription="false"
            data-mute="true">
    </player>
    <div class="desc">{!! nl2br($newPost->description->text) !!}</div>

</div>
<br>
