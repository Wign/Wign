<?php

namespace App\Http\Controllers;

use App\Il;
use App\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Description;
use App\Video;
use App\Word;
use App\Post;
use App\Tag;

use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Self_;
use PHPUnit\Framework\Constraint\IsEmpty;
use URL;
use App\Helpers\Helper;

define( 'REGEXP', config( 'wign.tagRegexp' ) );

class PostController extends Controller
{
    /**
     * Display the "create a post" view with the relevant data attached.
     * If a word is set, it's checked if it already has a post to it.
     *
     * @param String $word the queried word. Nullable.
     *
     * @return \Illuminate\View\View of "create a post"
     */
    public function getPostIndex( $word = null ) {
        $user = Auth::user();

        if ($user->isEntry())   {
            $now = Carbon::now()->toDateTimeString();
            $oneDayAgo = Carbon::now()->subDays(1)->toDateTimeString();
            $post_count = $user->postsCreator()->whereBetween('created_at', [$oneDayAgo, $now])->count();

            if ($post_count >= 1)    {
                return redirect()->back()->with('message', 'Maximum bidrag er nået for i dag. Prøv igen i morgen');
            }
        }

        if ( empty( $word ) ) {
            return view( 'create' );
        }

        $wordData        = Word::find($word);
        $data['hasPost'] = empty( $wordData ) ? 0 : 1;
        $data['word']    = empty( $wordData ) ? $word : $wordData->word;

        return view( 'create' )->with( $data );
    }

    public function postNewPost( Request $request ) {
        // Validating the incoming request
        $this->validate($request, [
            'word'              => 'required|string',
            'description'       => 'nullable|string',
            'wign01_uuid'       => 'required',
            'wign01_vga_mp4'    => 'required',
            'wign01_vga_thumb'  => 'required',
            'wign01_qvga_thumb' => 'required',
        ] );

        $user = Auth::user();

        //TODO fix so the fill only do when the word is new
        $word = Word::firstOrCreate( [
            'word' => $request->input('word') ,
        ],[
            'creator_id' => $user->id,
            'editor_id' => $user->id,
        ]);

        $video = new Video([
            'user_id' => $user->id,
            'camera_uuid'         => config('wign.cameratag.id'),
            'recorded_from'       => $request->input('recorded_from'),
            'video_uuid'          => $request->input('wign01_uuid'),
            'video_url'           => $request->input('wign01_vga_mp4'),
            'thumbnail_url'       => $request->input('wign01_vga_thumb'),
            'small_thumbnail_url' => $request->input('wign01_qvga_thumb')
        ]);
        $video->save();

        $desc = new Description([
            'creator_id' => $user->id,
            'editor_id' => $user->id,
            'text' => $request->input('description') === null ? "" : $request->input('description')
        ]);
        $desc->save();
        self::_updateTags( $desc );

        $rank = $request->input('il');
        $post = self::_create($user, $word, $video, $desc, $rank);

        if ($user->isEntry())  {
            self::_countPosts();    //Check if there shall trigger the voting of promotion for this user
            $post->delete(); //Keep invisible during the voting.
            session(['oldPost' => null, 'newPost' => $post->id ]);  // Will be loaded in VotingController
            return redirect()->action('VotingController@postNewReview');
        }

        $flash['url'] = URL::to( config( 'wign.urlPath.create' ) );

        return redirect( config( 'wign.urlPath.sign' ) . '/' . $word->word )->with( $flash );
    }

    /**
     * Show the matched posts for this word page.
     * Display all the posts if $word is non-null and does exist in database
     * Otherwise show the 'no post' page
     *
     * @param string $word - a nullable string with the query $word
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getPosts( $word ) {

        $word      = Helper::underscoreToSpace( $word );
        $wordModel = Word::whereWord( $word )->has('posts')->first();

        // If word exist in database
        if ( isset( $wordModel ) ) {
            $posts = $wordModel->posts()->get();
            foreach( $posts as $post)   {
                $content = self::_replaceTagsToURL($post->description->text);
                $post->likes_count = $post->video->likes->count();
                $post->descText = $content;
                $post->liked = $post->video->likes->contains(Auth::user());

                $post->inVoting = $post->ils()->first()->reviewsOldIl()->where('decided', 0)->count() > 0;
            }
            return view( 'post' )->with( array( 'word' => $wordModel->word, 'posts' => $posts ) );
        }

        // If no word exist in database; make a list of suggested word and display the 'no sign' view.
        $suggestions = $this->_getAlikeWords( $word, 5 );

        return view( 'nopost' )->with( compact([ 'word' , 'suggestions' ]) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */public function getEdit($id)
    {
        $post = Post::findOrFail($id);
        $post->rank = $post->il()->rank;


        if (Auth::user()->qcv()->rank == 0)    {
            return back()->with('message', 'Du har ikke rette-rettighed');
        }
        $word = $post->word;
        $video = $post->video;
        $desc = $post->description;

        return view('form.editPost')->with(compact(['word', 'video', 'desc', 'post']));
    }

    public function postEdit($id, Request $request) //TODO: validate the edit method
    {
        $user = Auth::user();
        $post = Post::find($id);

        $newWord = Word::firstOrCreate([
            'word' => $request->input('word')
        ],[
            'creator_id' => $post->word->creator->id,
            'editor_id' => $user->id
        ]);

        $newVideo = $request->input('wign01_uuid') === null ?
            $post->video :
            Video::firstOrCreate([
                'video_uuid' => $request->input('wign01_uuid')
            ],[
                'user_id' => $user->id,
                'post_id' => $post->id,
                'camera_uuid'         => config('wign.cameratag.id'),
                'recorded_from'       => $request->input('recorded_from'),
                'video_url'           => $request->input('wign01_vga_mp4'),
                'thumbnail_url'       => $request->input('wign01_vga_thumb'),
                'small_thumbnail_url' => $request->input('wign01_qvga_thumb')
            ]);

        $newDesp = $post->description->text == $request->input('description') ?
            $post->description :
            Description::create([
                'text' => $request->input('description'),
                'creator_id' => $post->description->creator->id,
                'editor_id' => $user->id
            ]);

        $newIl = $request->input('il');

        if ($newWord != $post->word || $newVideo != $post->video || $newDesp != $post->description) {
            $newPost = self::_create($user, $newWord, $newVideo, $newDesp, $newIl, $post->creator);

            if ($user->isAdmin() || $post->il()->rank <= $user->qcv()->rank)    {
                $post->delete();    //Only show the new one.
                $flash['url'] = URL::to( config( 'wign.urlPath.create' ) );
                return redirect( config( 'wign.urlPath.sign' ) . '/' . $post->word->word )->with( $flash );
            } else {
                $newPost->delete(); //Keep invisible during the voting.
                session(['oldPost' => $post->id, 'newPost' => $newPost->id ]);  // Will be loaded in VotingController
                return redirect()->action('VotingController@postNewReview');
            }
        } else if ($newIl != $request->input('il') && $post->il()->rank <= $user->qcv()->rank) {
            $post->ils()->delete();
            $post->ils()->save(new Il(['rank' => $newIl]));
        } else {
            return redirect()->back()->with('message', config('text.edit.no.change'));
        }
    }

    public function removePost($id)
    {
        $post = Post::find($id);
        $video = Video::find('post_id', $id);
        $video->delete();
        $description = Description::find('post_id', $id);
        $description->delete();
        $post->delete();

        return redirect()->route('index')->with('message', 'Opslaget er fjernet.');
    }

    /**
     * Show the recent # words which have been assigned with a post
     *
     * @param int $number of recent results
     *
     * @return \Illuminate\View\View
     */
    public function showRecent( $number = 25 ) {
        $words = Word::has('posts')->latest( $number )->get();

        return view( 'list' )->with( compact([ 'words', 'number' ]) );
    }

    /**
     * Show all words with assigned post, sorted by word ASC
     *
     * @return \Illuminate\View\View
     */
    public function showAll() {
        $words = Word::withCount('posts')->orderBy('posts_count', 'DESC')->orderBy('word')->get(['word', 'posts_count']);

        return view( 'listAll' )->with( compact('words') );
    }

    //////////////////////

    private function _create($user, $word, $video, $desp, $rank, $creator = null) {
        $newPost = new Post([
            'creator_id' => $creator === null ? $user->id : $creator->id,
            'editor_id' => $user->id,
            'word_id' => $word->id,
            'video_id' => $video->id,
            'description_id' => $desp->id,
        ]);
        $newPost->save();
        $newPost->ils()->delete();
        $newPost->ils()->save(new Il(['rank' => $rank]));

        return $newPost;
    }

    private function _countPosts()
    {
        $user = Auth::user();
        $sinceThisQCV = $user->qcv()->created_at;
        $today = Carbon::now()->toDateTimeString();

        $post_counts = $user->postsCreator()->whereBetween('created_at', [$sinceThisQCV, $today])->count();
        if ($post_counts % 10 == 0) {
            redirect()->action('UserController@promoteUser', ['id' => $user->id]);
        }
    }

    private static function _replaceTagsToURL( string $text ): string {
        $replaceWith = '<a href="' . URL::to( config( "wign.urlPath.tags" ) ) . '/$1">$0</a>';
        $text        = preg_replace( REGEXP, $replaceWith, $text );

        return $text;
    }

    private function _updateTags( $desc )    {
        $desc->tags()->detach(); // Update the tag relations
        if ( !empty( $desc->description ) ) {
            preg_match_all( REGEXP, $desc, $hashtags ); //Store the unique tags in $hashtags
            if ( !empty( $hashtags ) ) {
                foreach ( $hashtags[1] as $hashtag ) {
                    $tag = Tag::firstOrCreate( [ 'tag' => $hashtag ] );
                    $desc->tags()->attach( $tag );
                }
            }
        }
    }

    /**
     * Searching for words that looks alike the queried $word
     * Current uses both "LIKE" mysql query and Levenshtein distance, and return $count words with the least distance to $word
     *
     * @param string $word
     *
     * @return array|null
     */
    private function _getAlikeWords( string $word, int $count ) {
        $max_levenshtein = 5;
        $min_levenshtein = PHP_INT_MAX;
        $words           = Word::has('posts')->get();
        $tempArr         = array();

        foreach ( $words as $compareWord ) {
            $levenDist = levenshtein( strtolower( $word ), strtolower( $compareWord->word ) );
            if ( $levenDist > $max_levenshtein || $levenDist > $min_levenshtein ) {
                continue;
            } else {
                $tempArr[ $compareWord->word ] = $levenDist;
                if ( count( $tempArr ) == $count + 1 ) {
                    asort( $tempArr );
                    $min_levenshtein = array_pop( $tempArr );
                }
            }
        };

        if ( empty( $tempArr ) ) {
            return null; // There are none word with nearly the same "sounding" as $word
        } else {
            asort( $tempArr );
            $suggestWords = [];
            foreach ( $tempArr as $key => $value ) {
                $suggestWords[] = $key;
            }

            return $suggestWords;
        }
    }

    /**
     * Nice little function to send a Slack greet using webhook each time a new sign is posted on Wign.
     * It's to keep us busy developers awake! Thank you for your contribution!
     *
     * @param String $word
     * @param \Illuminate\Database\Eloquent\Model $sign - the $sign object, from which we can extract the information from.
     */
    private function sendSlack( $word, $post ) {
        $url     = URL::to( config( 'wign.urlPath.sign' ) . '/' . $word );
        $video   = 'https:' . $post->video->video_url;
        $message = [
            "attachments" => [
                [
                    "fallback"     => "Videoen kan ses her: " . $video . "!",
                    "color"        => "good",
                    "pretext"      => "Et ny tegn er kommet!",
                    "title"        => $word,
                    "title_link"   => $url,
                    "text"         => "Se <" . $video . "|videoen>!",
                    "unfurl_links" => true,
                    "image_url"    => "https:" . $video->thumbnail_url,
                    "thumb_url"    => "https:" . $video->small_thumbnail_url,
                ]
            ],
        ];
        Helper::sendJSON( $message, config( 'social.slack.webHook' ) );
    }

}
