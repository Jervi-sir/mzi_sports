<?php

namespace App\Helpers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class Helper
{
    static function getAuth()
    {
        $baseUrl = URL::to('/');
        if($user = Auth()->user()) {
            $data = [
                'loggedIn' => true,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'pic' => $baseUrl . '/' . $user->pic,
            ];
        } else {
            $data = [
                'loggedIn' => false,
                'uuid' => 'uuid',
                'name' => 'name',
                'email' => 'email',
                'pic' => 'pic',
            ];
        }

        return $data;
    }

    static function getTags() {
        $tags = Tag::all();
        foreach ($tags as $key => $tag) {
            $data[$key] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'active' => false,
            ];
        }

        return $data;
    }

    static function getPost($post) {
        $baseUrl = URL::to('/');

        $data = [
            'id' => $post->id,
            'type' => $post->type,
            'media_link' => $post->media_link,
            'media' => $baseUrl . '/' . $post->media,
            'description' => $post->description,
            'tags' => $post->tags,
            'views' => '123',
        ];

        return $data;
    }

    static function getUser($user) {
        $baseUrl = URL::to('/');

        $data = [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'profile_link' => $baseUrl . '/u' . '/' . $user->uuid,
            'pic' => $baseUrl . '/' . $user->pic,

        ];

        return $data;
    }

    static function getUserDetailed($uuid) {
        $baseUrl = URL::to('/');
        $user = User::where('uuid', $uuid)->first();
        $other = json_decode($user->other);
        $posts = $user->posts;

        $data = [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'location' => $user->location,
            'bio' => $other->bio,
            'link' => $other->link,
            'pic' => $baseUrl . '/' . $user->pic,
            'postCount' => $posts->count(),
            'followers' => $user->followers()->count(),
            'following' => $user->followings()->count(),
        ];

        return $data;
    }

    static function getUserPosts($uuid) {
        $baseUrl = URL::to('/');
        $base1 = URL::to('/p') . '/';

        $user = User::where('uuid', $uuid)->first();
        $posts = $user->posts;
        $auth = Auth()->user();
        $data = [];
        foreach ($posts as $key => $post) {
            $likes = $post->usersLike;
            $data[$key] = [
                'type' => $post->type,
                'url' => $base1 . $post->media_link,             //use uuid
                'media' => $baseUrl . '/' . $post->media,
                'media_link' => $post->media_link,
                'description' => $post->description,
                'tags' => $post->tags,
                'liked' => $likes->contains($auth->id) ? true : false,
                'nbLikes' => $likes->count(),
            ];
        }

        return $data;
    }

    static function random() {
        //my age
        $birthDate = "08/07/1998";
        $birthDate = explode("/", $birthDate);
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        $random = Str::random($age);

        return $random;
    }

    static function getMediaType($media) {
        //Get media type
        $mediaType = $media->getClientMimeType();
        if (strpos($mediaType, 'video') !== false) {
            $mediaType = 'video';
        } else {
            $mediaType = 'image';
        }

        return $mediaType;
    }

    static function tagToString($tags) {
        $tagsName = [];
        foreach($tags as $tag) {
            array_push($tagsName, json_decode($tag, true)['name']);
        }

        return implode(", ", $tagsName);
    }


}
