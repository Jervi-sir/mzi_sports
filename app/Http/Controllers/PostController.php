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
use Intervention\Image\Facades\Image;
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

        $tagsName = Helper::tagToString(json_decode($request->tags));
        $random = Helper::random();
        $isWithBadge = true;
        if(json_decode($request->badge)->id < 0) {
            $isWithBadge = false;
        }
        $uploadedFile = $this->cloudUploadFile($request->file('media'), false, $request->badge, $request->mediaHeight, $request->mediaWidth);

        $post = new Post;
        $post->user_id = Auth()->user()->id;
        $post->type = Helper::getMediaType($request->file('media'));
        $post->media_link = $random;

        $post->media = $uploadedFile['secure_path'];
        $post->public_id = $uploadedFile['public_id'];

        $post->thumbnail = Helper::getThumbnailURL($uploadedFile['secure_path']);
        $post->description = $request->description;
        $post->tags = $tagsName;
        $post->save();

        foreach(json_decode($request->tags) as $tag) {
            $tagId = $tag->id;
            $post->tags()->attach($tagId);
        }
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
            if($leader->followers->contains($auth->id)) { $doesFollow = true; }
        }

        $data['doesFollow'] = $doesFollow;
        return view('posts.view', ['data' => json_encode($data)]);
    }

    public function view2($uuid)
    {
        $post = Post::where('media_link', $uuid)->first();
        $user = $post->user()->first();

        if(Auth()->user()) {
            $data['post'] = Helper::getPostAuth($post);
        } else {
            $data['post'] = Helper::getPost($post);

        }
        $data['user'] = Helper::getUser($user);
        $data['auth'] = Helper::getAuth();
        $thumbnail = ($data['post']['thumbnail']);
        $description = ($data['post']['description']);

        $doesFollow = false;
        if($auth = Auth()->user()) {
            $leader = User::where('uuid', $user->uuid)->first();
            if($leader->followers->contains($auth->id)) { $doesFollow = true; }
        }

        $data['doesFollow'] = $doesFollow;
        return view('posts.view', ['data' => json_encode($data),
                                    'thumbnail' => $thumbnail,
                                    'description' => $description,
                                    'uuid' => $post->id]);
    }

    public function delete($uuid) {
        //select post
        $post = Post::find($uuid);
        //delete media from cloud

        //detach tags from it
        $public_id = $post->public_id;
        $uploadedFileUrl = Cloudinary::destroy($public_id);

        $post->delete();

        return redirect()->route('profile.mine');

    }

    /*************************************** */
    /************** HELPERS **************** */
    private function cloudUploadFile($file, $isWithBadge, $badge, $height, $width) {
        $badge = json_decode($badge);

        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 365);
        ini_set('max_execution_time', 365);

        // get media Type
        $mediaType = Helper::getMediaType($file);
        if($mediaType == 'image') {
            $uploadUrl = $this->uploadImage($file, $badge, $height, $width, $isWithBadge);
        } else {
            $uploadUrl = $this->uploadVideo($file, $badge, $height, $width, $isWithBadge);
        }

        return $uploadUrl;
    }

    private function uploadImage($file, $badge, $height, $width, $withbadge = true) {
        $iamgeDimensions = getimagesize($file);
        $width = $iamgeDimensions[0];
        $uploadedFileUrl = Cloudinary::upload($file->getRealPath(),[
            'folder' => 'images',
            'quality' => 'auto',
            'gravity' => 'north_east',
            'width' => number_format($width * 10 / 100),
            'crop' => 'scale',
            'overlay' => [
                'public_id' => $withbadge ? $badge->public_id : 'v1641691734/badges/transparent_bchpjc.png',
            ],
        ]);
        $data['secure_path'] = $uploadedFileUrl->getSecurePath();
        $data['public_id'] = $uploadedFileUrl->getPublicId();

        return $data;
    }

    private function uploadVideo($file, $badge, $height, $width, $withbadge = true) {
        ini_set('max_execution_time', 180); //3 minutes
        $uploadedFileUrl = Cloudinary::uploadVideo($file->getRealPath(), [
            'folder' => 'video',
            'quality' => 'auto',
            'gravity' => 'north_east',
            'width' => number_format($width * 10 / 100),
            'crop' => 'scale',
            'overlay' => [
                'public_id' => $withbadge ? $badge->public_id : 'v1641691734/badges/transparent_bchpjc.png',
            ],
        ]);

        $data['secure_path'] = $uploadedFileUrl->getSecurePath();
        $data['public_id'] = $uploadedFileUrl->getPublicId();

        return $data;
    }
}

