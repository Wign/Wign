<?php

namespace App\Http\Controllers;

use App\Post;
use App\Video;
use App\Description;
use Auth;
use Gate;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function postCreate(Request $request){
        if (!Auth::check()) {
            return redirect()->back();
        }
        $this->validate($request,[
            'word_id' => 'required',
            //'video_id' => 'required'
        ]);
        $user = Auth::user();
        $post = new Post([
            'word_id' => $request->input('word_id'),
        ]);
        $user->posts()->save($post);
        return redirect()->route('/')->with('info', $request>input('word') . ' er oprettet. Tak for dit bidrag');
    }

    public function getPostEdit(Request $request)
    {
        $user = Auth::user();
        if (!Auth::check()) {
            return redirect()->back();
        }
        $post = Post::find($request->input('id'));
        if ( $user->QCV < $post->IL){
            //TODO: Trigger the review process
        }
        $post->word_id = $request->input('word_id');
        $post->save();
        return view('sign/{word_id}');
    }

    public function getNewPosts()
    {
        $posts = Post::orderBy('created_at', 'desc')->get(10);
        return view('sign.new', ['posts' => $posts]);
    }

    public function getPost($id)
    {
        $post = Post::where('id', $id)->with(likes)->first();
        return view('post.index', ['post' => $post]);
    }

    public function getLikePost($id)
    {
        $post = Post::find($id);
        $like = new Like();
        $post->likes()->attach($like);
        $post->save($like);
    }

    public function getPostDelete($id)
    {
        $post = Post::find($id);
        $post->likes()->detach();
        $post->delete();
        return redirect()->route('index')->with('info', 'Tegnet er fjernet.');
    }
}
