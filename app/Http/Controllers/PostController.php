<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Session\Store;

class PostController extends Controller
{
    public function postCreate(Request $request){
        if (!Auth::check()) {
            return redirect()->back();
        }
        $this->validate($request,[
            'word_id' => 'required',
            'video_id' => 'required',
        ]);
        $user = Auth::user();
        $post = new Post([
            'word_id' => $request->input('word_id'),
            'video_id' => $request->input('video_id')
        ]);
        $user->posts()->save($post);

        return redirect()->route('/')->with('info', $request>input('word') . ' er oprettet. Tak for dit bidrag');
    }

    public function postUpdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $post = Post::find($request->input('id'));
        if (Gate::denies('update-post', $post)){
            //TODO: Trigger the review process
        }
        $post->word_id = $request->input('word_id');
        $post->video_id = $request->input('video_id');
        $post->description_id = $request->input('description_id');
        $post->save();

        return view('sign/{word_id}');
    }

    public function getNewPosts(Request $request)
    {
        $posts = Post::orderBy('created_at', 'desc')->get(10);

        return view('sign.new', ['posts' => $posts]);
    }
}
