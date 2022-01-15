@extends('layouts.master')

@section('title')
<title>mzi sports</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="../css/home.css">
<link rel="stylesheet" href="../css/tagList.css">
@endsection

@section('content')
@include('home.tagList')
<header>
    <img src="../pics/logo.svg" alt="">
</header>
<main >
    <!--
    <div class="tab-select">
        <span v-for="(tag, index) in tags">
            <a href="#" @click="filterByTag(tag, index)" :class="{ active: tag.active }">@{{ tag.name }}</a>
        </span>
        <span>
            <a href="#" @click.previent="moreTags"> see more ... </a>
        </span>
    </div>
-->
    <div class="tab-title">
        <h3>
           Actuality
        </h3>
    </div>
    @guest
    <div id="toastr-login" class="please-login hide">
        <span>
            Please Login
        </span>
    </div>
    @endguest
    <div class="result-wide" >
        <div href="#" class="card" v-for="(result, index) in results">
            <div class="top">
                <div class="owner">
                    <a :href="result.user.profile_link">
                        <img :src='result.user.pic' alt="">
                    </a>
                    <div class="details">
                        <a :href="result.user.profile_link" class="username">
                            @{{result.user.name}}
                        </a>
                    </div>
                </div>
            </div>
            <div  v-if="result.type != 'video'">
                <img class="card-img" :src="result.media" alt="">
            </div>
            <div  v-else>
                <video :src="result.media" controls/>
            </div>
            <div class="stats">
                @auth
                <a :id="'like_post' + index" v-if="!result.liked" href="#" class="heart" @click.prevent="like(result.media_link, index)">
                    <img src="../pics/heart_empty.svg" alt="">
                    <span>@{{ result.nbLikes }}</span>
                </a>
                <a v-else href="#" class="heart" @click.prevent="unlike(result.media_link, index)">
                    <img src="../pics/heart_full.svg" alt="">
                    <span>@{{ result.nbLikes }}</span>
                </a>
                @endauth
                @guest
                <a :id="'like_post' + index" href="#" class="heart" @click.prevent="toastr">
                    <img src="../pics/heart_empty.svg" alt="">
                    <span>@{{ result.nbLikes }}</span>
                </a>
                @endguest

                <div class="ago">
                    @{{result.created_at}}
                </div>
                <a class='share-btn share-btn-facebook' :href="result.sharefb" rel='nofollow' target='_blank'>
                    <img src="../pics/share.svg" alt="">
                </a>
            </div>
            <div class="comment">
                <button @click="viewComments(index, result.id, result.sharefb)">View all comments ( @{{ result.nb_comments }} )</button>
                <a :href='result.url'>Check the post</a>
            </div>
        </div>
    </div>
    <div class="load-more">
        <img v-bind:class="{ 'show' : loadIconShow }" src="../pics/loading-mini.svg" alt="">
        <button v-if="canLoadMore" @click="morePosts">More Posts</button>
    </div>

    <footer>
        <h6>copyright MZI sports</h6>
    </footer>

    <div id="comment-modal" class="comment-modal">
        <div class="top">
            <button @click='hideComments'><img src="../pics/arrow-back.svg" alt=""></button>
            <span>Comments</span>
            <a class='share-btn share-btn-facebook' :href="commentModal.link_share" rel='nofollow' target='_blank'>
                <img src="../pics/share.svg" alt="">
            </a>
        </div>
        <div class="comments">
            <form class="add-comment" action="{{ route('comment.store') }}" method="POST" @submit.prevent="comment">
                <div class="auth-img">
                    <img :src='auth.pic' alt="">
                </div>
                <div class="body-comment">
                    <input name="post_id" type="text" :value='commentModal.post_id' hidden>
                    <textarea name="comment" v-model="commentBody" id="" ></textarea>
                    <button type="submit" :disabled="commentBody == ''">Post</button>
                </div>
            </form>
            <div class="old-comments">
                <div class="latest-comments" v-for="(comment, index) in comments">
                    <div class="user-img">
                        <img :src='comment.pic' alt="">
                    </div>
                    <div class="body-comment">
                        <span>
                            <b>@{{ comment.user }}</b> @{{comment.body}}
                        </span>
                        <span class="created">
                            @{{comment.created_at}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('vuejs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var app = new Vue({
        el: '#body',
        data: {
            loadIconShow: false,
            canLoadMore: true,
            results: [],
            posts: [],
            tempArray: [],
            tags: [],
            allTags: [],
            auth: [],
            selectedTag: '',
            token: '',
            current: '',
            commentBody: '',
            comments: {
                'user': '',
                'pic': '',
                'body': '',
                'created_at': '',
            },
            commentModal: {
                'post_id': '',
                'link_share': '',
                'post_index': '',
            }

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
            moreTags: function() {
                var tagMenu = document.getElementById('mobile-tags');
                tagMenu.classList.add('active');
            },
            closeMoreTags: function() {
                var tagMenu = document.getElementById('mobile-tags');
                tagMenu.classList.remove('active');
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
            toastr: function() {
                var toastr = document.getElementById("toastr-login");
                toastr.classList.remove('hide');
                toastr.classList.add('show');
                setTimeout(() => {
                    toastr.classList.remove('show')
                }, 100);
                setTimeout(() => {
                    toastr.classList.add('hide')
                }, 5000);
            },
            like: function(media_link, index) {
                var ele = 'like_post' + index;
                document.getElementById(ele).classList.add('liked');
                this.results[index].nbLikes++;
                this.results[index].liked = true;
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
            unlike: function(media_link, index) {
                const url = '{!! route('unlike') !!}';
                var ele = 'like_post' + index;
                setTimeout(function() { document.getElementById(ele).classList.remove('liked')}, 10);
                this.results[index].nbLikes--;
                this.results[index].liked = false;
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
            morePosts: function() {
                const url = '{!! route('morePosts') !!}';
                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({ currentPage: this.current}),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                }

                this.loadIconShow = true;

                fetch(url, fetchData)
                    .then(response => response.json())
                    .then((data) => {
                        var dataResponse = JSON.parse(data);
                        dataResponse.posts.forEach((item, index)=>{
                            this.results.push(item)
                        });
                        console.log(this.results);
                        this.current = dataResponse.visited;
                        this.loadIconShow = false;
                        this.loadIconShow = false;
                    })
                    .catch((error) => {
                        this.canLoadMore = false;
                        console.log(error)
                        this.loadIconShow = false;
                    });
            },
            comment: function(e) {
                var comment = e.target.querySelector('textarea').value;
                var post_id = e.target.querySelector('input').value;
                const url = '{!! route('comment.store') !!}';
                let fetchData = {
                    method: 'POST',
                    body: JSON.stringify({ comment: comment, post_id: post_id}),
                    headers: new Headers({
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.token
                    })
                }
                fetch(url, fetchData)
                    .then((response) => {
                        var new_comment = {
                            'user': this.auth.name,
                            'pic': this.auth.pic,
                            'body': this.commentBody,
                            'created_at': 'Just Now',
                        };
                        this.comments.unshift(new_comment);
                        this.commentBody = '';

                        this.results[this.commentModal.post_index].push(new_comment);
                    })
                    .catch((e) => console.log(e));

            },
            viewComments: function(index, post_id, link_share) {
                var comment_modal = document.getElementById('comment-modal').classList.add('show')
                this.comments = this.results[index].comments;
                this.commentModal.post_id = post_id;
                this.commentModal.link_share = link_share;
                this.commentModal.post_index = index;
                console.log(this.commentModal);

            },
            hideComments: function() {
                var comment_modal = document.getElementById('comment-modal').classList.remove('show')
            }

        },
        created() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            var data = JSON.parse({!! json_encode($data) !!});

            this.results = data.posts;
            this.tags = data.tags;
            this.auth = data.auth;
            this.allTags = data.allTags;

            this.tags[0].active = true;
            this.selectedTag = this.tags[0].name;
            this.current = data.visited;

            console.log(data);
        }
    })
</script>

@endsection
