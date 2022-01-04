<?php

use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/p/{uuid}', [PostController::class, 'view'])->name('post.view');

Route::get('/u/{uuid}', [ProfileController::class, 'view'])->name('profile.view');

Route::middleware(['auth'])->group(function () {
    Route::get('/post&add', [PostController::class, 'add'])->name('post.add');
    Route::post('/post&add', [PostController::class, 'store'])->name('post.store');

    Route::get('/profile&mine', [ProfileController::class, 'myProfile'])->name('profile.mine');
    Route::get('/editProfile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/editProfile', [ProfileController::class, 'save'])->name('profile.save');

    Route::post('/followUser', [FollowerController::class, 'follow'])->name('follow');
    Route::post('/unfollowUser', [FollowerController::class, 'unfollow'])->name('unfollow');

    Route::post('/likePost', [LikeController::class, 'like'])->name('like');
    Route::post('/unlikePost', [LikeController::class, 'unlike'])->name('unlike');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
