<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth()->user()) {
            $data['posts'] = $this->getPostsOnAuth();
        } else {
            $data['posts'] = $this->getPosts();
        }
        $data['tags'] = $this->getNTags(5);
        $data['allTags'] = $this->getTags();
        $data['auth'] = Helper::getAuth();

        return view('home.home2', ['data' => json_encode($data)]);
    }

    private function getPostsOnAuth()
    {
        $baseUrl = URL::to('/');
        $base1 = URL::to('/p') . '/';
        $posts = Post::latest()->get();
        $auth = Auth()->user();
        foreach ($posts as $key => $post) {
            $likes = $post->usersLike;
            $nbLikes = $post->usersLike->count();
            $user = $post->user()->first();
            $data[$key] = [
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'sharefb' => 'https://www.facebook.com/sharer/sharer.php?u=0' . $base1 . $post->media_link,
                'liked' => $likes->contains($auth->id) ? true : false,
                'nbLikes' => $nbLikes,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'name' => $user->name,
                    'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
                    'pic' => $user->pic,
                ]
            ];
        }

        return $data;
    }

    private function getPosts()
    {
        $base1 = URL::to('/p') . '/';
        $baseUrl = URL::to('/');
        $posts = Post::all();
        foreach ($posts as $key => $post) {
            $nbLikes = $post->usersLike->count();
            $user = $post->user()->first();
            $data[$key] = [
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'sharefb' => 'https://www.facebook.com/sharer/sharer.php?u=0' . $base1 . $post->media_link,
                'liked' => true,
                'nbLikes' => $nbLikes,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'name' => $user->name,
                    'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
                    'pic' => $user->pic,
                ]
            ];
        }

        return $data;
    }

    private function getTags()
    {
        $tags = Tag::orderBy('name', 'asc')->get();
        foreach ($tags as $key => $tag) {
            $data[$key] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => false,
            ];
        }

        return $data;
    }

    private function getNTags($amount = 10)
    {
        $tags = Tag::latest()->take($amount)->get();
        foreach ($tags as $key => $tag) {
            $data[$key] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => false,
            ];
        }

        return $data;
    }
}
