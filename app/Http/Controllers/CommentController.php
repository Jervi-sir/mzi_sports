<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request) {


        $request->validate([
            'comment' => 'required',
            'post_id' => 'required',
        ]);

        $input = $request->all();
        $comment = new Comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth()->user()->id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'response' => 'sucess'
        ]);
    }
}
