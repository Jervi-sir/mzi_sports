@extends('layouts.master')

@section('title')
<title>profile</title>
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
        @guest
        <a href="{{ route('login') }}">Follow</a>
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

    <div class="result">
        <div href="#" class="card" v-for="(post, index) in results">
            <a :href="post.url">
                <img class="card-img" :src="post.media" alt="">
            </a>
            <div class="stats">
                <a v-if="!post.liked" :id="'like_post' + index"href="#" class="heart" @click.prevent="like(post.media_link, index)">
                    <img src="../pics/heart.svg" alt="">
                </a>
                <a v-else href="#" class="heart" @click.prevent="unlike(post.media_link, index)">
                    <img src="../pics/heart_full.svg" alt="">
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
            results: [],
            user: [],
            auth: [],
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
                            var tmp = parseInt(this.user.followers) + 1;
                            this.user.followers = tmp;

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
                            var intFollowers = parseInt(this.user.followers);
                            if(intFollowers >= 1) {
                                var tmp = intFollowers - 1;
                                this.user.followers = tmp;
                            }
                        }
                    })
                    .catch((error) => {console.log(error)});
            },
            like: function(media_link, index) {
                const url = '{!! route('like') !!}';

                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({
                        media_link: media_link,
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
                            //this.following = true;
                            this.results[index].liked = true;
                            var ele = 'like_post' + index;
                            document.getElementById(ele).classList.add('liked');
                        }
                    })
                    .catch((error) => {console.log(error)});
            },
            unlike: function(media_link, index) {
                const url = '{!! route('unlike') !!}';

                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({
                        media_link: media_link,
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
                            //this.following = false;
                            var ele = 'like_post' + index;
                            setTimeout(function() {
                                document.getElementById(ele).classList.remove('liked');
                            }, 10);
                            this.results[index].liked = false;
                        }
                    })
                    .catch((error) => {console.log(error)});
            }
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            this.results = JSON.parse({!! json_encode($posts) !!});
            this.auth = JSON.parse({!! json_encode($auth) !!});
            this.user = JSON.parse({!! json_encode($user) !!});
            this.following = JSON.parse({!! json_encode($doesFollow) !!});
            console.log(this.results);
        }
    })
</script>

@endsection

