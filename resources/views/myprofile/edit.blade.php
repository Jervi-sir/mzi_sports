@extends('layouts.master')

@section('title')
<title>home</title>
@endsection

@section('style-header')
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyC4rKcS59yGTyRUd-Oa5aw9gHaHEN4WAIk"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.js"></script>

<link rel="stylesheet" href="../css/editProfile.css">
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
    <form action="{{ route('profile.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="preview-image">
            <img :src="user.pic" alt="">
        </div>
        <div class="field add-file" >
            <label for="add-file">Change Profile Pic</label>
            <input name="media" @change="onFileChange"  id="add-file" type="file" placeholder="add image" accept="image/*">
        </div>
        <div class="field">
            <label for="name" class="label">Name</label>
            <input v-model="user.name" id="name" name="name" type="text" placeholder="Name" required>
        </div>
        <div class="field read-only">
            <label for="email" class="label">Email</label>
            <input v-model="user.email" id="email" type="email" placeholder="Email" readonly required>
        </div>
        <div class="field">
            <label for="phone" class="label">Phone</label>
            <input id="phone" v-model="user.phone_number" maxlength="14" v-on:keypress="phoneNumber" name="phone_number" type="text" placeholder="Phone number" required>
        </div>
        <div class="field geo">
            <label for="geo" class="label" >
                <img src="pics/map.svg" alt="">
            </label>
            <input v-model="user.location" id="geo" type="text" name="location" placeholder="choose your Nation" required>
        </div>

        <div class="field">
            <label for="link" class="label">Link</label>
            <input v-model="user.link" id="link" name="link" type="text" placeholder="link" required>
        </div>
        <div class="field">
            <label for="bio" class="label">Bio</label>
            <textarea name="bio" id="bio"  required>@{{ user.bio }}</textarea>
        </div>
        <div class="space"></div>
        <button type="submit">Save</button>
    </form>
</main>
@endsection

@section('vuejs')

<script>
    var app = new Vue({
        el: '#body',
        data: {
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
            },
            onFileChange: function(e) {
                const file = e.target.files[0];
                this.user.pic = URL.createObjectURL(file);
            },
            phoneNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 43) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            }
        },
        mounted() {
            this.token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            this.user = JSON.parse({!! json_encode($user) !!});
            this.auth = JSON.parse({!! json_encode($auth) !!});
            $("#geo").geocomplete();
        }
    })
</script>

@endsection

@section('style-footer')

@endsection

