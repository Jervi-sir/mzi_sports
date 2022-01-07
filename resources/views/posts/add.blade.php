@extends('layouts.master')

@section('title')
<title>home</title>
@endsection

@section('style-header')
<link rel="stylesheet" href="../css/add.css">
<script src="https://unpkg.com/vue-select@latest"></script>


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
        <div v-else class="preview-image">
            <img :src="media" onerror="" alt="">
        </div>
        <div class="field add-file" :class="errorDetected.media ? 'error-input' : ''">
            <label for="add-file">add image or video</label>
            <input name="media" @change="onFileChange" id="add-file" type="file" placeholder="add image" accept="video/mp4,video/x-m4v,video/*,image/*" required>
        </div>
        <span class="error" v-if="errorDetected.media">
            @{{errorText.media}}
        </span>
        <div class="field" :class="errorDetected.description ? 'error-input' : ''">
            <textarea v-model="description" name="description" name="" id="" placeholder="Description" required></textarea>
        </div>
        <span class="error" v-if="errorDetected.description">
            @{{errorText.description}}
        </span>
        <div class="field" :class="errorDetected.tags ? 'error-input' : ''">
            <v-multiselect v-model="tagValues" tag-placeholder="Add this as new tag" placeholder="Search or add a tag" label="name" track-by="id" :options="tagOptions" :multiple="true" :taggable="true"></v-multiselect>
        </div>
        <span class="error" v-if="errorDetected.tags">
            @{{errorText.tags}}
        </span>
        <div class="field location-badges" :class="errorDetected.badge ? 'error-input' : ''">
            <v-multiselect v-model="badgeValue" placeholder="Select your Location" label="id" track-by="id" :options="badgeOptions" :option-height="104" :custom-label="customLabel" :show-labels="false">
                <template slot="option" slot-scope="props"><img class="option__image" :src="props.option.img" alt="Select your Location">
                    <div class="option__desc"><span class="option__title">@{{ props.option.title }}</span></div>
                </template>
                <template slot="singleLabel" slot-scope="props"><img class="option__image" :src="props.option.img" alt="Select your Location"><span class="option__desc"><span class="option__title">@{{ props.option.title }}</span></span></template>
              </v-multiselect>
        </div>
        <span class="error" v-if="errorDetected.badge">
            @{{errorText.badge}}
        </span>
        <input name="tags" v-model="tags" type="text" hidden required>
        <input name="badge" v-model="badge" type="text" hidden required>
        <button type="submit" @click="verifySubmition">Publish</button>
    </form>
</main>
@endsection

@section('vuejs')
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
<script>
    var app = new Vue({
        el: '#body',
        components: {
            "v-multiselect": window.VueMultiselect.default,
        },
        data: {
            auth: [],
            token: '',
            media: ' ',
            isVideo: false,
            description: '',

            tags: [],
            tagValues: [],
            tagOptions: [],

            badge: [],
            badgeValue: '',
            badgeOptions: [{ title: ' Pirate',  img: '' }],

            errorDetected: {
                media: false,
                tags: false,
                badge: false,
                description: false,
            },
            errorText: {
                media: 'media missing',
                tags: 'tags missing',
                badge: 'location missing',
                description: 'description missing',
            }
        },
        methods: {
            verifySubmition: function() {
                //check if media exists
                if(this.media.replace(/\s+/g, '') == '') {
                    this.errorDetected.media = true;
                }
                if(this.tags.length === 0) {
                    this.errorDetected.tags = true;
                }
                if(this.badge.length === 0) {
                    this.errorDetected.badge = true;
                }
                if(this.description.length === 0) {
                    this.errorDetected.description = true;
                }
            },
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
                this.errorDetected.media = false;
                if(type) {
                    this.isVideo = true;
                } else {
                    this.isVideo = false;
                }
                this.media = URL.createObjectURL(file);
            },
            customLabel: function({ title, desc }) {
                return `${title} – ${desc}`
            },
            fillingDescription: function() {
                this.errorDetected.description = false;
            },

        },
        watch: {
            tagValues: function(){
                this.errorDetected.tags = false;
                this.tags = JSON.stringify(this.tagValues);
            },
            badgeValue: function(){
                this.errorDetected.badge = false;
                this.badge = JSON.stringify(this.badgeValue);
            },
            description: function(){
                this.errorDetected.description = false;
            },
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            this.tagOptions = JSON.parse({!! json_encode($tags) !!});
            this.auth = JSON.parse({!! json_encode($auth) !!});
            this.badgeOptions = JSON.parse({!! json_encode($badges) !!});
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

    .location-badges .multiselect__option, .location-badges .multiselect__single{
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        justify-content: space-between;
    }

    .location-badges .multiselect__option img, .location-badges .multiselect__single img {
        width: 10rem;
        margin-right: 4rem
    }

    .multiselect__single {
        padding-left: 1rem;
        margin-bottom: unset;
        font-size: 1.3rem;
        opacity: 0.5;

    }

    .multiselect__input, .multiselect__single {
        margin-bottom: unset;
        background: transparent
    }

    .multiselect__tags {
        margin-top: 0;
        margin-bottom: 0 !important;
        display: flex;
        padding: unset;
        border-radius: 5px;
        border: 1px solid #e8e8e8;
        background: transparent;
        font-size: 14px;
        align-items: center;
    }

</style>
@endsection

