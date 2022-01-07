<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showTags() {
        $tags = Tag::orderBy('id', 'desc')->get();
        return view('admin.tagsAll', ['tags' => $tags]);
    }

    public function saveTag(Request $request) {
        $tag = new Tag();
        $tag->name = $request->tag;
        $tag->save();
        return redirect()->route('tags.show');
    }
}
