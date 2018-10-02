<?php

namespace App\Http\Controllers;

use App\Description;
use App\Video;
use App\Word;
use Illuminate\Http\Request;
use App\Post;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /* OLD
         if ( empty( $word ) ) {
        return view( 'create' );
        }

        $wordData        = $this->word_service->getWordByWord( $word );
        $data['hasSign'] = empty( $wordData ) ? 0 : 1;
        $data['word']    = empty( $wordData ) ? $word : $wordData->word;

        return view( 'create' )->with( $data );
         */
        $this->validate($request,[
            'word' => 'required',
            'video_uuid' => 'required'
        ]);
        //Task: Auth the user and store in $user
        //Task: Check if the word exists in DB (either with another post or as request
        $word = new Word([
            'string' => $request->input('word'),
            'user_id' => null,  // Auth::check()
            'language_id' => null
        ]);
        $post = new Post([
            'word_id' => $word->id,
            'language_id' => null,
            'user_id' => null
        ]);
        $video = new Video([
            'user_id' => null,
            'post_id' => $post,
            'playings' => 0, //Unnecessary?
            'camera_uuid',
            'recorded_from',
            'video_uuid'          => $request->input('wign01_uuid'),
            'video_url'           => $request->input('wign01_vga_mp4'),
            'thumbnail_url'       => $request->input('wign01_vga_thumb'),
            'small_thumbnail_url' => $request->input('wign01_qvga_thumb')
        ]);
        $description = new Description([
            'user_id' => null,
            'post_id' => $post,
            'description' => $request->input('description')
        ]);
        $post->save();
        $word->save();
        $video->save();
        $description->save();

        return redirect()->route('create')->with('info', 'Videoen er lagt op under' . $request->input('word') . '. Tak for dit bidrag');
    }

    public function getPosts($word_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Trigger which part which is edited and perform the edit
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
