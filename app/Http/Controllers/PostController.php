<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;

class PostController extends Controller
{
    public function add()
    {
        $data['tags'] = Helper::getTags();
        $data['auth'] = Helper::getAuth();

        return view('posts.add', ['tags' => json_encode($data['tags']),
                                    'auth' => json_encode($data['auth'])
                                ]);
    }

    public function store(Request $request)
    {
        // Tag String
        $tagsName = Helper::tagToString($request->tags);
        // get media Type
        $mediaType = Helper::getMediaType($request->file('media'));
        //generate media link
        $random = Helper::random();
        //upload media
        $path = $request->file('media')->store('media');

        $post = new Post;
        $post->user_id = 1;
        $post->type = $mediaType;
        $post->media_link = $random;
        $post->media = $path;
        $post->description = $request->description;
        $post->tags = $tagsName;
        $post->save();

        //attach tags to the post
        foreach($request->tags as $tag) {
            //get id
            $tagId = json_decode($tag, true)['id'];
            //attach it to the post
            $post->tags()->attach($tagId);
        }
        //return to the post
        return redirect()->route('home');
    }

    public function view($uuid)
    {
        $post = Post::where('media_link', $uuid)->first();
        $user = $post->user()->first();

        $data['post'] = Helper::getPost($post);
        $data['user'] = Helper::getUser($user);
        $data['auth'] = Helper::getAuth();

        $doesFollow = false;
        if($auth = Auth()->user()) {
            $leader = User::where('uuid', $user->uuid)->first();
            if($leader->followers->contains($auth->id)) {
                $doesFollow = true;
            }
        }

        return view('posts.view', ['post' => json_encode($data['post']),
                                    'auth' => json_encode($data['auth']),
                                    'user' => json_encode($data['user']),
                                    'doesFollow' => json_encode($doesFollow)]);
    }


    /*************************************** */
    /************** HELPERS **************** */








}

