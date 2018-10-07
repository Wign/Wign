<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Description;
use App\Video;
use App\Word;
use App\Post;
use App\Tag;

use URL;
use App\Helpers\Helper;

define( 'REGEXP', config( 'wign.tagRegexp' ) );

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the "create a post" view with the relevant data attached.
     * If a word is set, it's checked if it already has a post to it.
     *
     * @param String $word the queried word. Nullable.
     *
     * @return \Illuminate\View\View of "create a post"
     */
    public function getPostIndex( $word = null ) {
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
        $request->validate( [
            'word'              => 'required|string',
            'description'       => 'nullable|string',
            'wign01_uuid'       => 'required',
            'wign01_vga_mp4'    => 'required',
            'wign01_vga_thumb'  => 'required',
            'wign01_qvga_thumb' => 'required',
        ] );
        $userID = 3306;    //Auth::check();

        $post = new Post([      // Prepare the new post
            'user_id' => $userID
        ]);
        $post->save();

        $word = Word::firstOrCreate( [ 'word' => $request->input('word') ] );
        $post->words()->attach($word, ['user_id' => $userID]);

        $video = new Video([
            'user_id' => $userID,
            'post_id' => $post->id,
            'playings' => 0, //Unnecessary?
            'camera_uuid'         => config('wign.cameratag.id'),
            'recorded_from'       => $request->input('recorded_from'),
            'video_uuid'          => $request->input('wign01_uuid'),
            'video_url'           => $request->input('wign01_vga_mp4'),
            'thumbnail_url'       => $request->input('wign01_vga_thumb'),
            'small_thumbnail_url' => $request->input('wign01_qvga_thumb')
        ]);
        $video->save();

        $desc = new Description([
            'user_id' => $userID,
            'post_id' => $post->id,
            'description' => $request->input('description') === null ? "" : $request->input('description')
        ]);
        $desc->save();

        $desc->tags()->detach(); // Update the tag relations
        if ( !empty( $desc->description ) ) {
            $tagArray = [];
            $hashtags = preg_match_all( REGEXP, $desc->description, $tagArray );
            if ( !empty( $hashtags ) ) {
                foreach ( $hashtags as $hashtag ) {
                    $tag = Tag::firstOrCreate( [ 'tag' => $hashtag ] );
                    $desc->tags()->attach( $tag );
                }
            }
        }

        /*
        if ( $sign ) {
            $this->sendSlack( $word, $sign );

            $flash = [
                'message' => __( 'flash.sign.created' ),
            ];
        } else {
            // Something went wrong! The sign isn't created!
            $flash = [
                'message' => __( 'flash.sign.create.failed' ),
            ];
        }
        */
        $flash['url'] = URL::to( config( 'wign.urlPath.create' ) );

        return redirect( config( 'wign.urlPath.sign' ) . '/' . $word )->with( $flash );
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
        $wordModel = Word::whereWord( $word )->with('posts')->first();

        // If word exist in database
        if ( isset( $wordModel ) ) {
            $posts = $wordModel->posts()->get();
            // $posts = $this->sign_service->getVotedSigns( $wordModel );
            // $posts = $posts->sortByDesc( 'num_votes' ); // Sort the signs according to the number of votes

            return view( 'post' )->with( array( 'word' => $wordModel->word, 'posts' => $posts ) );
        }

        // If no word exist in database; make a list of suggested word and display the 'no sign' view.
        $suggestWords = $this->getAlikeWords( $word, 5 );

        return view( 'nopost' )->with( [ 'word' => $word, 'suggestions' => $suggestWords ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return null; // Trigger which part which is edited and perform the edit
    }

    public function delete($id)
    {
        $post = Post::find($id);
        $video = Video::find('post_id', $id);
        $video->delete();
        $description = Description::find('post_id', $id);
        $description->delete();
        $post->delete();
        return redirect()->route('index')->with('info', 'Tegnet er fjernet.');
    }


    //////////////////////

    /**
     * Show the recent # words which have been assigned with a post
     *
     * @param int $number of recent results
     *
     * @return \Illuminate\View\View
     */
    public function showRecent( $number = 25 ) {
        $recent = Word::with('posts')->latest( $number )->get();

        return view( 'list' )->with( [ 'words' => $recent, 'number' => $number ] );
    }

    /**
     * Show all words with assigned post, sorted by word ASC
     *
     * @return \Illuminate\View\View
     */
    public function showAll() {
        $words = Word::withCount('posts')->orderBy('posts_count')->get(['word', 'posts_count']);

        return view( 'listAll' )->with( [ 'words' => $words ] );
    }

    /**
     * Searching for words that looks alike the queried $word
     * Current uses both "LIKE" mysql query and Levenshtein distance, and return $count words with the least distance to $word
     *
     * @param string $word
     *
     * @return array|null
     */
    private function getAlikeWords( string $word, int $count ) {
        $max_levenshtein = 5;
        $min_levenshtein = PHP_INT_MAX;
        $words           = Word::with('posts')->get();
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

}
