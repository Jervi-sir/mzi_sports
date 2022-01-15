@extends('layouts.master')

@section('title')
<title>Mzi posts</title>
<meta property="og:url"                content="http://www.nytimes.com/2015/02/19/arts/international/when-great-minds-dont-think-alike.html" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="Mzi sports, Actuality and News" />
<meta property="og:image"              content="{{$thumbnail}}" />
<meta property="og:description"        content="{{ $description }}" />

@endsection

@section('style-header')
<link rel="stylesheet" href="../css/view.css">
@endsection

@section('content')
<header>
    <a href="" class="logo-menu">
        <!--<img src="pics/logo-menu.svg" alt="">-->
    </a>
    <h2> Mzi Sports</h2>
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
            <div class="dropdown">
                <div class="dropbtn icons btn-right showLeft" @click="showDropdown">
                    <img src="../pics/three_dots_green.svg" alt="">
                </div>
                <div id="myDropdown" class="dropdown-content">
                    <a href="#">Copy Link</a>
                    <a href="#">Edit</a>
                    <a href="#" @click.prevent="showDeleteModal">Delete</a>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="media" v-if="post.type === 'video' " >
                <video :src="post.media" controls/>
                <img class="play-button" src="../pics/play_button.svg" alt="">
            </div>
            <div class="media" v-else>
                <img :src="post.media" alt="">
            </div>
        </div>
        <div class="reactions">
            <a id='like_post' v-show="!post.liked" href="#" class="heart" @click.prevent="like(post.media_link)">
                <img src="../pics/heart_empty.svg" alt="">
                <span>@{{ post.nbLikes }} Likes</span>
            </a>
            <a v-show="post.liked" href="#" class="heart" @click.prevent="unlike(post.media_link)">
                <img src="../pics/heart_full.svg" alt="">
                <span>@{{ post.nbLikes }} Likes</span>
            </a>
            <div class="views">
            </div>

        </div>
    </div>
    <div id="delete-modal" class="delete-modal">
        <div class="top">
            <span class="danger">
                Delete post
            </span>
            <button @click="showDeleteModal">x</button>
        </div>
        <h3>Are you sure to delete this Post ?</h3>
        <form class="actions" action="{{ route('delete.post', ['uuid' => $uuid]) }}" method="POST">
            @csrf
            <button type="submit" class="yes" @click="showDeleteModal">Yes</button>
            <button type="button" class="no" @click="showDeleteModal">No</button>
        </form>
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
            },
            like: function(media_link) {
                var ele = 'like_post';
                document.getElementById(ele).classList.add('liked');
                this.post.nbLikes++;
                this.post.liked = true;
                const url = '{!! route('like') !!}';
                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({media_link: media_link}),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                };
                fetch(url, fetchData)
                    .then(response => response.json())
                    .then((data) => {})
                    .catch((error) => {console.log(error)});
            },
            unlike: function(media_link) {
                const url = '{!! route('unlike') !!}';
                var ele = 'like_post';
                setTimeout(function() { document.getElementById(ele).classList.remove('liked')}, 10);
                this.post.nbLikes--;
                this.post.liked = false;
                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({ media_link: media_link}),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                }
                fetch(url, fetchData)
                    .then(response => response.json())
                    .then((data) => {})
                    .catch((error) => {console.log(error)});
            },
            showDropdown: function() {
                document.getElementById("myDropdown").classList.toggle("show");
            },
            showDeleteModal: function() {
                document.getElementById("delete-modal").classList.toggle("show");
                document.getElementById("myDropdown").classList.remove("show");
            }
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            var data = JSON.parse({!! json_encode($data) !!});
            this.post = data.post;
            this.user = data.user;
            this.auth = data.auth;
            console.log(this.post);
            this.following = data.doesFollow;
        },
        created() {
            var link = document.createElement('meta');
            link.setAttribute('property', 'og:title');
            link.content = 'this.post.thumbnail';
            document.getElementsByTagName('head')[0].appendChild(link);
        },
    })
</script>

@endsection

@section('style-footer')

@endsection

