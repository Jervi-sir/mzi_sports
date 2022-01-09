<?php

namespace App\Helpers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Badges;
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
                'pic' => $user->pic,
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

    static function getBadges() {
        $badges = Badges::all();
        foreach ($badges as $key => $badge) {
        $location = json_decode($badge->location);
        $data[$key] = [
            'id' => $badge->id,
            'img' => $badge->url,
            'public_id' => $badge->public_id,
            'title' =>  $location->en,
            'ar' =>  $location->ar,
            'fr' =>  $location->fr,
        ];
        }

        $withoutBadge = [
            'id' => -1,
            'img' => 'Error.src',
            'public_id' => ' ',
            'title' =>  'Unknown Location',
            'ar' =>  ' ',
            'fr' =>  ' ',
        ];

        $tempArray = collect($data)->sortBy('title')->toArray();
        $finalData = [];

        foreach ($tempArray as $key => $item) {
            array_push($finalData, $item);
        }

        array_push($finalData, $withoutBadge);

        return $finalData;
    }


    static function getPost($post) {
        $baseUrl = URL::to('/');
        $nbLikes = $post->usersLike->count();
        $user = $post->user()->first();
        $data = [
            'id' => $post->id,
            'type' => $post->type,
            'media_link' => $post->media_link,
            'media' => $post->media,
            'thumbnail' => $post->thumbnail,
            'description' => $post->description,
            'tags' => $post->tags,
            'views' => '123',
            'liked' => true,
            'nbLikes' => $nbLikes < 2 ? $nbLikes . ' Like' : $nbLikes . ' Likes',
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
            'pic' => $user->pic,

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
            'bio' => $other !== null ? $other->bio : '',
            'link' => $other !== null ? $other->link : '',
            'pic' => $user->pic,
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
        if($auth) {
            foreach ($posts as $key => $post) {
                $likes = $post->usersLike;
                $data[$key] = [
                    'type' => $post->type,
                    'url' => $base1 . $post->media_link,             //use uuid
                    'thumbnail' => $post->thumbnail,
                    'media' => $post->media,
                    'media_link' => $post->media_link,
                    'description' => $post->description,
                    'tags' => $post->tags,
                    'liked' => $likes->contains($auth->id) ? true : false,
                    'nbLikes' => $likes->count(),
                ];
            }
        } else {
            foreach ($posts as $key => $post) {
                $likes = $post->usersLike;
                $data[$key] = [
                    'type' => $post->type,
                    'url' => $base1 . $post->media_link,             //use uuid
                    'thumbnail' => $post->thumbnail,
                    'media' => $post->media,
                    'media_link' => $post->media_link,
                    'description' => $post->description,
                    'tags' => $post->tags,
                    'liked' => false,
                    'nbLikes' => $likes->count(),
                ];
            }
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
            array_push($tagsName, $tag->name);
        }
        return implode(", ", $tagsName);
    }

    static function getThumbnailURL($url) {
        $u1 = explode('.', $url);
        $lengthU1 = count($u1);
        $newUrl = str_replace($u1[$lengthU1 - 1], 'jpg', $url);

        return $newUrl;
    }
}
