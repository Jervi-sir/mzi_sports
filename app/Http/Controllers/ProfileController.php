<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class ProfileController extends Controller
{

    public function view($uuid)
    {
        if($auth = Auth()->user()) {
            if($auth->uuid == $uuid) {
                return redirect()->route('profile.mine');
            }
        }

        $data['user'] = Helper::getUserDetailed($uuid);
        $data['posts'] = Helper::getUserPosts($uuid);
        $data['auth'] = Helper::getAuth();

        $doesFollow = false;
        if($auth = Auth()->user()) {
            $leader = User::where('uuid', $uuid)->first();
            if($leader->followers->contains($auth->id)) {
                $doesFollow = true;
            }
        }

        return view('profile.view', ['user' => json_encode($data['user']),
                                     'auth' => json_encode($data['auth']),
                                      'posts' => json_encode($data['posts']),
                                    'doesFollow' => json_encode($doesFollow)]);
    }

    public function myProfile()
    {
        $user = Auth()->user();
        $data['user'] = Helper::getUserDetailed($user->uuid);
        $data['posts'] = Helper::getUserPosts($user->uuid);
        $data['auth'] = Helper::getAuth();

        return view('myprofile.view', ['user' => json_encode($data['user']),
                                        'auth' => json_encode($data['auth']),
                                        'posts' => json_encode($data['posts'])]);
    }

    public function edit()
    {
        $user = Auth()->user();
        $data['user'] = Helper::getUserDetailed($user->uuid);
        $data['auth'] = Helper::getAuth();

        return view('myprofile.edit', ['user' => json_encode($data['user']),
                                        'auth' => json_encode($data['auth'])]);
    }

    public function save(Request $request)
    {
        $data['bio'] = $request->bio;
        $data['link'] = $request->link;
        $user = Auth()->user();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->location = $request->location;
        $user->other = json_encode($data);

        if($request->hasFile('media')) {
            $path = $request->file('media')->store('profile_pics');
            $user->pic = $path;
        }

        $user->save();

        return redirect()->route('profile.mine');
    }

    /******************************************** */
    /**************** Helpers ******************* */


}
