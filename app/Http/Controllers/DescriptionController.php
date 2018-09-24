<?php

namespace App\Http\Controllers;

use App\Models\Description;
use Illuminate\Http\Request;

class DescriptionController extends Controller
{
    public function addDescription(Request $request)
    {
        $desp = new Description([
            'description' => $request->input('description')
        ]);
        $desp->save();
        $desp->attach($request->input('tags') === null ? [] : $request->input('tags'));

        return view('/');
    }

    public function getDescription(Request $request)
    {
        return view('/');
    }

    public function editDescription(Request $request)
    {
        $desp = Description::find($request->input('id'));
        $desp->description = $request->input('description');
        $desp->save();
        $desp->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));

        return view('/');
    }

    public function deleteDescription($id)
    {
        $desp = Description::find($id);
        $desp->tags()->detach();
        $desp->delete();

        return view('/');
    }
}
