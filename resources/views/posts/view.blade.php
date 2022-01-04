@extends('layouts.master')

@section('title')
<title>post</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="../css/view.css">
@endsection

@section('content')
<header>
    <a href="" class="logo-menu">
        <!--<img src="pics/logo-menu.svg" alt="">-->
    </a>
    <h2>@{{ post.type }}</h2>
    <a href="javascript:history.go(-1)" class="back"><img src="../pics/arrow-back.svg" alt=""></a>
</header>
<main>
    <div class="post">
        <div class="top">
            <div class="owner">
                <a :href="user.profile_link" class="profile-pic">
                    <img :src="user.pic" alt="">
                </a>
                <div class="details">
                    <a :href="user.profile_link" class="username">
                        @{{user.name}}
                    </a>
                    <div v-if="auth.uuid !== user.uuid">
                        @guest
                        <a class="guest-login" href="{{ route('login') }}">Follow</a>
                        @endguest
                        @auth
                        <form v-if="!following" id="follow-form" action="{{ route('follow') }}">
                            @csrf
                            <button type="button" @click="follow">Follow</button>
                        </form>
                        <form v-else id="unfollow-form" action="{{ route('unfollow') }}">
                            @csrf
                            <button type="button" @click="unfollow">Following</button>
                        </form>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
        <div class="content">
            <div class="media">
                <img :src="post.media" alt="">
                <img v-if="post.type === 'video' " class="play-button" src="../pics/play_button.svg" alt="">
            </div>
        </div>
        <div class="reactions">
            <form action="">
                @csrf
                <button type="submit"><img src="../pics/heart_empty_black.svg" alt=""></button>
            </form>
            <div class="views">
                @{{ post.views }}
            </div>
        </div>

        <div class="comments">
            comments
        </div>
    </div>
</main>
@endsection

@section('vuejs')

<script>
    var app = new Vue({
        el: '#body',
        data: {
            post: [],
            auth: [],
            user: [],
            token: '',
            following: true,
        },
        methods: {
            settings: function() {
                var menu = document.getElementById('mobile-setting');
                menu.classList.add('active');
            },
            closeSettings: function() {
                var menu = document.getElementById('mobile-setting');
                menu.classList.remove('active');
            },
            follow: function() {
                const form = document.getElementById('follow-form');
                const url = form.action;
                const csrf = form.children[0].value;

                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({
                        uuid: this.user.uuid,
                    }),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                }
                fetch(url, fetchData)
                    .then(response => response.json())
                    .then((data) => {
                        if(data['response'] == true) {
                            this.following = true;
                        }
                    })
                    .catch((error) => {console.log(error)});
            },
            unfollow: function() {
                const form = document.getElementById('unfollow-form');
                const url = form.action;
                const csrf = form.children[0].value;

                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({
                        uuid: this.user.uuid,
                    }),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                }
                fetch(url, fetchData)
                    .then(response => response.json())
                    .then((data) => {
                        if(data['response'] == true) {
                            this.following = false;
                        }
                    })
                    .catch((error) => {console.log(error)});
            }
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            this.post = JSON.parse({!! json_encode($post) !!});
            this.user = JSON.parse({!! json_encode($user) !!});
            this.auth = JSON.parse({!! json_encode($auth) !!});
            this.following = JSON.parse({!! json_encode($doesFollow) !!});
            console.log(this.user)
            console.log(this.auth)
        }
    })
</script>

@endsection

@section('style-footer')

@endsection

