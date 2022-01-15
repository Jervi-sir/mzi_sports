<?php

namespace App\Http\Controllers\Blade;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function view2($uuid) {

        $post = Post::where('media_link', $uuid)->first();
        $user = $post->user()->first();

        $auth = Auth()->user();

        $nbLikes = $post->usersLike->count();
        $likes = $post->usersLike;
        $comments = $post->comments()->latest()->get();
        foreach ($comments as $ckey => $comment) {
            $comment_array[$ckey] = [
                'id' => $comment->id,
                'body' => $comment->comment,
                'user' => $comment->user->name,
                'pic' => $comment->user->pic,
                'created_at' => $comment->created_at->diffForHumans(),
            ];
        }

        $data['post'] = [
            'id' => $post->id,
            'type' => $post->type,
            'media_link' => $post->media_link,
            'media' => $post->media,
            'thumbnail' => $post->thumbnail,
            'description' => $post->description,
            'tags' => $post->tags,
            'views' => '123',
            'liked' => $auth ?  ($likes->contains($auth->id) ? true : false) : false,
            'nbLikes' => $nbLikes,
            'created_at' => $post->created_at->diffForHumans(),
            'nb_comments' => $comments->count(),
            'comments' => $comments ? $comment_array : '',
        ];

        $baseUrl = URL::to('/');
        $data['user'] = [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
            'pic' => $user->pic,

        ];

        if($auth) {
            $data['auth'] = [
                'loggedIn' => true,
                'uuid' => $auth->uuid,
                'name' => $auth->name,
                'email' => $auth->email,
                'pic' => $auth->pic,
            ];
        } else {
            $data['auth'] = [
                'loggedIn' => false,
                'uuid' => 'uuid',
                'name' => 'name',
                'email' => 'email',
                'pic' => 'pic',
            ];
        }

        $doesFollow = false;
        if($auth = Auth()->user()) {
            $leader = User::where('uuid', $user->uuid)->first();
            if($leader->followers->contains($auth->id)) { $doesFollow = true; }
        }
        $data['doesFollow'] = $doesFollow;

        //is owner
        $data['isOwner'] = false;
        if($auth) {
            if($user->id == $auth->id) {
                $data['isOwner'] = true;
            } else {
                $data['isOwner'] = false;
            }
        }


        return view('blade.posts.view', ['data' => json_encode($data),
                                        'uuid' => $data['post']['id'],
                                        'isOwner' => $data['isOwner']]);
    }
}
