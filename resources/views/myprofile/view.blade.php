@extends('layouts.master')

@section('title')
<title>my profile</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="../css/profile.css">
@endsection

@section('content')
<header>
    <a href="" class="logo-menu">
        <!--<img src="../pics/logo-menu.svg" alt="">-->
    </a>
    <h2>@{{ user.name }}</h2>
    <a href="javascript:history.go(-1)" class="back"><img src="../pics/arrow-back.svg" alt=""></a>
</header>
<main>
    <div class="profile-top">
        <div class="profile-image">
            <img :src="user.pic" alt="">
        </div>
        <div class="profile-stats">
            <div class="ele">
                <span class="nb">@{{ user.postCount }}</span>
                <span class="text" v-if="user.postCount <= 1">Post</span>
                <span class="text" v-else>Posts</span>
            </div>
            <div class="ele">
                <span class="nb">@{{ user.followers }}</span>
                <span class="text" v-if="user.postCount <= 1">Follower</span>
                <span class="text" v-else>Followers</span>
            </div>
            <div class="ele">
                <span class="nb">@{{ user.following }}</span>
                <span class="text" >Following</span>
            </div>
        </div>
    </div>
    <div class="profile-details">
        <div class="username">
            <h5>@{{ user.name }}</h5>
        </div>
        <div class="bio">
            <span>@{{ user.bio }}</span>
        </div>
        <div class="link">
            <a :href="user.link">@{{ user.link }}</a>
        </div>
    </div>
    <div class="actions">
        <a href="{{ route('profile.edit') }}" type="submit">Edit</a>
    </div>

    <div class="result">
        <div href="#" class="card" v-for="(post, index) in posts">
            <a :href="post.url" v-if="post.type != 'video'">
                <img class="card-img" :src="post.thumbnail" alt="">
            </a>
            <a :href="post.url" v-else>
                <img class="play-button" src="../pics/play_button.svg" alt="">
                <img class="card-img" :src="post.thumbnail" alt="">
            </a>
            <div class="stats">
                <a href="#" class="heart">
                    <img src="../pics/heart.svg" alt="">
                </a>
            </div>
        </div>
    </div>


</main>
@endsection

@section('vuejs')

<script>
    var app = new Vue({
        el: '#body',
        data: {
            posts: [],
            user: [],
            auth: [],
            token: '',
        },
        methods: {
            settings: function() {
                var menu = document.getElementById('mobile-setting');
                menu.classList.add('active');
            },
            closeSettings: function() {
                var menu = document.getElementById('mobile-setting');
                menu.classList.remove('active');
            }
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            var data = JSON.parse({!! json_encode($data) !!});
            this.posts = data.posts;
            this.auth = data.auth;
            this.user = data.user;
            console.log(data)
        }
    })
</script>

@endsection

@section('style-footer')
<style>
    video {
        height: 100%;
        width: 100%;
    }
</style>
@endsection
