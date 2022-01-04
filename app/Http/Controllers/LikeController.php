<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function like(Request $request)
    {
        $media_link = $request->media_link;

        $auth = Auth()->user();

        $post = Post::where('media_link', $media_link)->first();

        //$post->usersLike()->attach($auth->id);
        if($post->usersLike->contains($auth->id)) {
            return [
                'response' => 'already Following',
            ];
        }

        //$post->usersLike->attach($auth->id);
        $auth->likePosts()->attach($post->id);

        return [
            'response' => true,
        ];
    }

    public function unlike(Request $request)
    {
        $media_link = $request->media_link;

        $auth = Auth()->user();

        $post = Post::where('media_link', $media_link)->first();

        //$post->usersLike()->attach($auth->id);


        //$post->usersLike->attach($auth->id);
        $auth->likePosts()->detach($post->id);

        return [
            'response' => true,
        ];
    }



}
