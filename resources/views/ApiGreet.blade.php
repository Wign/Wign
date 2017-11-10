<h1>Welcome to Wign API</h1>
<p>We currently offer the following API-calls:</p>

<p>
    <strong><code>GET http://api.wign.dk/words/{query}</code></strong><br>
    JSON<br>
    Return a list of words that begin or end with {query}. Returns empty array if no words is found.<br>
    - It is a good way to search for the right word, for example when using autocompletion.
</p>

<p>
    <strong><code>GET http://api.wign.dk/hasSign/{word}</code></strong><br>
    JSON<br>
    Returns whether we has at least one sign for {word}, in the format <code>{word:boolean}</code><br>
    - The boolean value will be <code>true</code> if we have at least one sign, <code>false</code> if we don't have the
    sign.
</p>

<p>
    <strong><code>GET http://api.wign.dk/video/{word}</code></strong><br>
    JSON<br>
    Returns a list of sign elements of {word}, including the following relevant data: <code>videoID, description, thumb,
        created_at</code>. Returns empty array if no words is found. Returns a full list of words if no {word} is
    provided.<br>
    - <code>VideoID</code> to show the video element. <code>description</code> of the video is provided by the "author"
    of the video. <code>thumb</code> is the thumbnail picture of the video in .png. <code>created_at</code> is the date
    of creation of the video.
</p>

<p>Contact <a href="mailto:{{ config('wign.email') }}">Troels Madsen</a> for more informations.</p>