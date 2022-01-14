<?php

namespace App\Http\Controllers\Blade;

use App\Models\Post;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function myProfile() {
        $auth = Auth()->user();

        return redirect()->route('profile.view', ['uuid' => $auth->uuid]);
    }

    public function view($uuid) {
        $user = User::where('uuid', $uuid)->first();
        $other = json_decode($user->other);
        $posts = $user->posts;
        $data['user'] = [
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
        $base1 = URL::to('/p') . '/';
        $auth = Auth()->user();
        foreach ($posts as $key => $post) {
            $likes = $post->usersLike;
            $data['posts'][$key] = [
                'type' => $post->type,
                'url' => $base1 . $post->media_link,             //use uuid
                'thumbnail' => $post->thumbnail,
                'square_pic' => Helper::getSquarePic($post->thumbnail),
                'media' => $post->media,
                'media_link' => $post->media_link,
                'description' => $post->description,
                'tags' => $post->tags,
                'liked' => $auth ? ($likes->contains($auth->id) ? true : false) : false,
                'nbLikes' => $likes->count(),
            ];
        }

        if($user = Auth()->user()) {
            $data['auth'] = [
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

        $doesFollow = false;
        if($auth = Auth()->user()) {
            $leader = User::where('uuid', $uuid)->first();
            if($leader->followers->contains($auth->id)) {
                $doesFollow = true;
            }
        }

        $data['doesFollow'] = $doesFollow;

        //is my profile
        $data['isMyProfile'] = false;
        if($auth) {
            if($uuid == $auth->uuid) {
                $data['isMyProfile'] = true;
            } else {
                $data['isMyProfile'] = false;
            }
        }

        return view('blade.profile.view', ['data' => json_encode($data),
                                    'isMyProfile' => $data['isMyProfile']]);
    }
}
