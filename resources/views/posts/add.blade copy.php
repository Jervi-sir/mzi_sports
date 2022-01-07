@extends('layouts.master')

@section('title')
<title>home</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.7.1/dist/style.css">
<link rel="stylesheet" href="../css/add.css">

@endsection

@section('content')
<header>
    <a href="" class="logo-menu">
        <!--<img src="pics/logo-menu.svg" alt="">-->
    </a>
    <h2>Post</h2>
    <a href="javascript:history.go(-1)" class="back"><img src="../pics/arrow-back.svg" alt=""></a>
</header>
<main>
    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div v-if="isVideo === true"  class="preview-image">
            <video :src="media" controls/>
        </div>
        <div v-else  class="preview-image">
            <img :src="media" alt="">
        </div>

        <div class="field add-file" >
            <label for="add-file">add image</label>
            <input name="media" @change="onFileChange" id="add-file" type="file" placeholder="add image" accept="video/mp4,video/x-m4v,video/*,image/*" >
        </div>
        <div class="field">
            <textarea name="description" name="" id="" placeholder="Description"></textarea>
        </div>
        <div class="field">
            <tags-input name="tags" element-id="tags"
                v-model="selectedTags"
                placeholder="Add a skill"
                :typeahead="true"
                :typeahead-hide-discard="true"
                :only-existing-tags="true"
                :existing-tags="existingTags"
                id-field="id"
                text-field="name">
            </tags-input>
        </div>
        <button type="submit">Publish</button>
    </form>
</main>
@endsection

@section('vuejs')

<script src="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.7.1/dist/voerro-vue-tagsinput.js"></script>

<script>
    var app = new Vue({
        el: '#body',
        components: { "tags-input": VoerroTagsInput },
        data: {
            selectedTags: [],
            existingTags: [],
            auth: [],
            token: '',
            media: '',
            isVideo: false,
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
            onFileChange: function(e) {
                const file = e.target.files[0];
                const type = file.type.includes('video');
                if(type) {
                    this.isVideo = true;
                } else {
                    this.isVideo = false;
                }
                this.media = URL.createObjectURL(file);
            },
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            this.existingTags = JSON.parse({!! json_encode($tags) !!});
            this.auth = JSON.parse({!! json_encode($auth) !!});
        }
    })
</script>

@endsection

@section('style-footer')
<style>
    .tags-input-root span {
        font-size: 1.3rem;
        font-weight: 400;
        /*padding: 0.5rem 0.7rem;*/
        display: inline-flex;
        align-items: center;
        background-color: white;
        color: #494949;
    }

    .tags-input-remove {
        top: unset;
    }

    .tags-input-wrapper-default {
        width: 100%;
        /* margin-top: 1.5rem; */
        background: rgba(196, 196, 196, 0);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }

    .tags-input-typeahead-item-highlighted-default {
        color: white !important;
    }
    .tags-input-remove:before, .tags-input-remove:after {
        background-color: black;
    }
</style>
@endsection

