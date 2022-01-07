@extends('layouts.master')

@section('title')
<title>mzi sports</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="../css/home.css">
@endsection

@section('content')
<header>
    <img src="../pics/logo.svg" alt="">
</header>
<main >
    <div class="tab-select">
        <span v-for="(tag, index) in tags">
            <a href="#" @click="filterByTag(tag, index)" :class="{ active: tag.active }">@{{ tag.name }}</a>
        </span>
        <span>
            <a href="#"> + </a>
        </span>
    </div>
    <div class="tab-title">
        <h3>
            @{{ selectedTag }}
        </h3>
    </div>

    <div class="result-wide">
        <div href="#" class="card" v-for="(result, index) in results">
            <a :href="result.url">
                <img class="card-img" :src="result.media" alt="">
            </a>
            <div class="stats">
                <a :id="'like_post' + index" v-if="!result.liked" href="#" class="heart" @click.prevent="like(result.media_link, index)">
                    <img src="../pics/heart.svg" alt="">
                </a>
                <a v-else href="#" class="heart" @click.prevent="unlike(result.media_link, index)">
                    <img src="../pics/heart_full.svg" alt="">
                </a>
            </div>
            <div class="ago">
                @{{result.created_at}}
            </div>
        </div>
    </div>


    <footer>
        <h6>copyright Jervi</h6>
    </footer>
</main>
@endsection

@section('vuejs')
<script>
    var app = new Vue({
        el: '#body',
        data: {
            results: [],
            posts: [],
            tempArray: [],
            tags: [],
            auth: [],
            selectedTag: '',
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
            },
            filterByTag: function(event, index) {
                this.tempArray = [];
                this.selectedTag = event.name;
                this.posts.forEach(element => {
                    var tags = element.tags;
                    if(tags.includes(event.name)) {
                        console.log(element.tags);
                        this.tempArray.push(element);
                    }
                });
                this.results = this.tempArray;

                //make the tag active
                var length = this.tags.length;
                for(var i = 0; i < length; i++) {
                    this.tags[i].active = false;
                }
                this.tags[index].active = true;

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
        created() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            var posts = JSON.parse({!! json_encode($posts) !!});
            var tags = JSON.parse({!! json_encode($tags) !!});
            var auth = JSON.parse({!! json_encode($auth) !!});

            this.posts = posts;
            this.tags = tags;
            this.results = this.posts;
            this.auth = auth;

            this.tags[0].active = true;
            this.selectedTag = this.tags[0].name;
            console.log(this.posts);
        }
    })
</script>

@endsection
