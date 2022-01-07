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
        $data['tags'] = $this->getTenTags(2);
        $data['auth'] = Helper::getAuth();

        return view('home.home2', ['posts' => json_encode($data['posts']),
                                    'auth' => json_encode($data['auth']),
                                    'tags' => json_encode($data['tags'])]);
    }

    public function tagList() {
        $tags = Tag::orderBy('name', 'asc')->get();
        $data['tags'] = $this->getTags();
        $data['auth'] = Helper::getAuth();

        return view('home.tagList', ['auth' => json_encode($data['auth']),
                                    'tags' => json_encode($data['tags'])]);
    }

    private function getPostsOnAuth()
    {
        $base1 = URL::to('/p') . '/';
        $posts = Post::latest()->get();
        $auth = Auth()->user();
        foreach ($posts as $key => $post) {
            $likes = $post->usersLike;
            $data[$key] = [
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'liked' => $likes->contains($auth->id) ? true : false,
                'nbLikes' => $likes->count(),
                'created_at' => $post->created_at->diffForHumans(),
            ];
        }

        return $data;
    }

    private function getPosts()
    {
        $base1 = URL::to('/p') . '/';
        $posts = Post::all();
        foreach ($posts as $key => $post) {
            $data[$key] = [
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'liked' => true,
                'nbLikes' => $post->usersLike->count(),
                'created_at' => $post->created_at->diffForHumans(),
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

    private function getTenTags($amount = 10)
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
