<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function follow(Request $request)
    {
        $uuid = $request->uuid;
        $auth_id = Auth()->user()->id;
        $leader = User::where('uuid', $uuid)->first();

        if($leader->id == $auth_id) {
            return [
                'response' => 'already Following',
            ];
        }

        if($leader->followers->contains($auth_id)) {
            return [
                'response' => 'already Following',
            ];
        }
        $leader->followers()->attach($auth_id);

        return [
            'response' => true,
        ];
    }

    public function unfollow(Request $request)
    {
        $uuid = $request->uuid;
        $leader = User::where('uuid', $uuid)->first();
        $auth_id = Auth()->user()->id;

        if($leader->id == $auth_id) {
            return [
                'response' => 'already Following',
            ];
        }

        if(!$leader->followers->contains($auth_id)) {
            return [
                'response' => 'already unFollowing',
            ];
        }

        $leader->followers()->detach($auth_id);

        return [
            'response' => true,
        ];
    }
}
