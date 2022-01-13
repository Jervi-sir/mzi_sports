<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\Blade\ProfileController as ProfileBlade;
use App\Http\Controllers\ProfileController as ProfileController;

use App\Http\Controllers\Blade\PostController as PostBlade;
use App\Http\Controllers\PostController as PostController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/', function () { return view('welcome'); });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/morePosts', [HomeController::class, 'morePosts'])->name('morePosts');

Route::get('/p/{uuid}', [PostBlade::class, 'view2'])->name('post.view');

Route::get('/u/{uuid}', [ProfileBlade::class, 'view'])->name('profile.view');

Route::middleware(['auth'])->group(function () {
    Route::get('/post&add', [PostController::class, 'add'])->name('post.add');
    Route::post('/post&add', [PostController::class, 'store'])->name('post.store');
    Route::post('/deletepost/{uuid}', [PostController::class, 'delete'])->name('delete.post');

    Route::get('/profile&mine', [ProfileBlade::class, 'myProfile'])->name('profile.mine');
    Route::get('/editProfile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/editProfile', [ProfileController::class, 'save'])->name('profile.save');

    Route::post('/followUser', [FollowerController::class, 'follow'])->name('follow');
    Route::post('/unfollowUser', [FollowerController::class, 'unfollow'])->name('unfollow');

    Route::post('/likePost', [LikeController::class, 'like'])->name('like');
    Route::post('/unlikePost', [LikeController::class, 'unlike'])->name('unlike');
});

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('admin/tags', [AdminController::class, 'showTags'])->name('tags.show');
    Route::post('admin/tags', [AdminController::class, 'saveTag'])->name('tag.save');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
