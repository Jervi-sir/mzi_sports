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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    public function add()
    {
        $data['tags'] = Helper::getTags();
        $data['auth'] = Helper::getAuth();
        $data['badges'] = Helper::getBadges();

        return view('posts.add', ['tags' => json_encode($data['tags']),
                                    'auth' => json_encode($data['auth']),
                                    'badges' => json_encode($data['badges'])
                                ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'tags' => ['required', 'string', 'max:255'],
            'badge' => ['required', 'string', 'max:255'],
        ]);

        // Tag String
        $tagsName = Helper::tagToString(json_decode($request->tags));
        //generate media link
        $random = Helper::random();

        //get location badge for watermark
        $badge = json_decode($request->badge);
        //upload media
        //$path = $request->file('media')->store('media');
        $uploadedFileUrl = $this->cloudUploadFile($request->file('media'), $isWithBadge = true, $badge);

        $post = new Post;
        $post->user_id = Auth()->user()->id;
        $post->type = Helper::getMediaType($request->file('media'));
        $post->media_link = $random;
        $post->media = $uploadedFileUrl;
        $post->description = $request->description;
        $post->tags = $tagsName;
        $post->save();

        //attach tags to the post
        foreach(json_decode($request->tags) as $tag) {
            //get id
            $tagId = $tag->id;
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

    private function cloudUploadFile($file, $isWithBadge, $badge) {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 365);
        ini_set('max_execution_time', 365);
        // get media Type
        $mediaType = Helper::getMediaType($file);
        if($isWithBadge) {
            if($mediaType == 'image') {
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),[
                    'folder' => 'images',
                    'transformation' => [
                        'width' => 1080, 'height' => 1080, 'crop' => 'limit',
                        'overlay' => [
                            'url' => $badge->img, 'flags' => 'layer_apply',
                            'public_id' => $badge->public_id,
                        ],
                        'gravity' => 'north_east',
                        'x' => 30, 'y' => 30,
                        'quality' => 'auto','fetch_format' => 'auto',
                    ]
                ])->getSecurePath();
            } else {
                ini_set('max_execution_time', 180); //3 minutes
                $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(), [
                    'folder' => 'video',
                    'transformation' => [
                        'quality' => 'auto',
                        'overlay' => [
                            'url' => $badge->img, 'flags' => 'layer_apply',
                            'public_id' => $badge->public_id,
                        ],
                        'gravity' => 'north_east',
                        'x' => 30, 'y' => 30
                    ],
                ])->getSecurePath();
            }
        }
        else {
            if($mediaType == 'image') {
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),[
                    'folder' => 'images',
                    'transformation' => [
                        'width' => 1080, 'height' => 1080, 'crop' => 'limit',
                        'quality' => 'auto','fetch_format' => 'auto',
                    ]
                ])->getSecurePath();
            } else {
                ini_set('max_execution_time', 180); //3 minutes
                $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(), [
                    'folder' => 'video',
                    'transformation' => ['quality' => 'auto'],
                ])->getSecurePath();
            }
        }
        return $uploadedFileUrl;
    }






}

