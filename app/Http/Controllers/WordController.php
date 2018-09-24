<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function addWord(Request $request)  {
        $this->validate($request,[
            'word' => 'required|min:1'
        ]);
        $word = new Word([
            'word' => $request->input('word'),
            'user_id' => null, //TODO
            'langauge_id' => null //TODO
        ]);
        $word->save();

        return redirect()->route('index')->with('info', 'Videoen er tilføjet under ordet: ' . $request->input('word'));
    }

    public function getWord($id)
    {
        $words = Word::find($id);
        return view('sign.word', ['words' => $words]);
    }

    public function getWords($word)
    {
        $words = Word::orderBy('word')->get();
        return view('list.words', ['words' => $words]);
    }

    public function updateWord (Request $request)
    {
        $this->validate($request,[
            'word' => 'required|min:1'
        ]);
        $word = Word::find($request->input('id'));
        $word->word = $request->input('word');
    }
}
