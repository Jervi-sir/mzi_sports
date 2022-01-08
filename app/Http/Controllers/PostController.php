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

        return view('posts.add', ['data' => json_encode($data)] );
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

        $data['doesFollow'] = $doesFollow;

        return view('posts.view', ['data' => json_encode($data)]);
    }


    /*************************************** */
    /************** HELPERS **************** */

    private function cloudUploadFile($file, $isWithBadge, $badge) {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 365);
        ini_set('max_execution_time', 365);

        $file_dimentions = getimagesize($file);
        $file_width = $file_dimentions[0];
        $file_height = $file_dimentions[1];
        $scale = 'c_scale,w_' . number_format($file_width * 1 / 100) . '/';

        $splitBadge = explode('upload/' ,$badge->img);
        $injectScale = $splitBadge[0] . 'upload/' . $scale . $splitBadge[1];

        $image = imagecreatefromjpeg($badge->img);
        $img = imagescale( $image, 500, 400 );
        dd(($img));

        dd($injectScale);
        // get media Type
        $mediaType = Helper::getMediaType($file);
        if($isWithBadge) {
            if($mediaType == 'image') {
                $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),[
                    'folder' => 'images',
                    'eager' => [['width' => 848,'height' => 480 ,'crop' => 'scale'],],
                    'transformation' => [
                        'overlay' => [
                            'url' => $injectScale, 'flags' => 'layer_apply',
                            'public_id' => $badge->public_id,
                        ],
                        'gravity' => 'north_east',
                        'x' => 10, 'y' => 10,
                        'quality' => 'auto','fetch_format' => 'auto',
                    ]
                ])->getSecurePath();
            } else {
                ini_set('max_execution_time', 180); //3 minutes
                $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(), [
                    'folder' => 'video',
                    'eager' => [['width' => 848,'height' => 480 ,'crop' => 'scale'],],
                    'transformation' => [
                        'quality' => 'auto',
                        'overlay' => [
                            'url' => $injectScale, 'flags' => 'layer_apply',
                            'public_id' => $badge->public_id,
                        ],
                        'gravity' => 'north_east',
                        'x' => 10, 'y' => 10,
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

