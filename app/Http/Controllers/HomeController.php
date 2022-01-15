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
        $currentPage = 1;
        $posts = Post::latest()->paginate(5, ['*'], 'page', $currentPage);
        if(Auth()->user()) {
            $getposts =  $this->getPostsOnAuth($posts);
            $data['posts'] = $getposts;
        } else {
            $getposts =  $this->getPosts($posts);
            $data['posts'] = $getposts;
        }
        $data['tags'] = $this->getNTags(5);
        $data['allTags'] = $this->getTags();
        $data['auth'] = Helper::getAuth();
        $data['visited'] = $currentPage;

        return view('home.home2', ['data' => json_encode($data)]);
    }

    public function morePosts(Request $request){
        $requestedPage = $request->currentPage + 1;
        $posts = Post::latest()->paginate(5, ['*'], 'page', $requestedPage);

        if(Auth()->user()) {
            $ajaxData['posts'] = $this->getPostsOnAuth($posts);
        } else {
            $ajaxData['posts'] = $this->getPosts($posts);
        }

        $ajaxData['visited'] = $requestedPage;

        return response()->json(json_encode($ajaxData), 200);
    }

    private function getPostsOnAuth($posts)
    {
        $baseUrl = URL::to('/');
        $base1 = URL::to('/p') . '/';
        $auth = Auth()->user();
        foreach ($posts as $key => $post) {
            $likes = $post->usersLike;
            $nbLikes = $post->usersLike->count();
            $user = $post->user()->first();
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
            $data[$key] = [
                'id' => $post->id,
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'thumbnail' => $post->thumbnail,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'sharefb' => 'https://www.facebook.com/sharer/sharer.php?u=' . $base1 . $post->media_link,
                'liked' => $likes->contains($auth->id) ? true : false,
                'nbLikes' => $nbLikes,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'name' => $user->name,
                    'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
                    'pic' => $user->pic,
                ],
                'nb_comments' => $comments->count(),
                'comments' => $comments ? $comment_array : '',
            ];

            $comment_array = [];
        }

        return $data;
    }

    private function getPosts($posts)
    {
        $base1 = URL::to('/p') . '/';
        $baseUrl = URL::to('/');
        foreach ($posts as $key => $post) {
            $nbLikes = $post->usersLike->count();
            $user = $post->user()->first();
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
            $data[$key] = [
                'id' => $post->id,
                'url' => $base1 . $post->media_link,             //use uuid
                'name' => $post->name,
                'media_link' => $post->media_link,
                'media' => $post->media,
                'thumbnail' => $post->thumbnail,
                'description' => $post->description,
                'tags' => $post->tags,
                'others' => $post->others,
                'type' => $post->type,
                'sharefb' => 'https://www.facebook.com/sharer/sharer.php?u=' . $base1 . $post->media_link,
                'liked' => false,
                'nbLikes' => $nbLikes,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'name' => $user->name,
                    'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
                    'pic' => $user->pic,
                ],
                'nb_comments' => $comments->count(),
                'comments' => $comments ? $comment_array : '',
            ];
            $comment_array = [];
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
            if($tag->posts()->count() > 0) {
                $data[$key] = [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'active' => false,
                ];
            }
        }

        return $data;
    }
}
