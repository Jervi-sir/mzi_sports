@extends('layouts.rootlogs')

@section('title')
<title></title>
@endsection

@section('style-header')
<link rel="stylesheet" href="css/logs.css">
@endsection

@section('script-header')
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyC4rKcS59yGTyRUd-Oa5aw9gHaHEN4WAIk"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
@endsection

@section('body')
<div id="login" class="form">
    <div class="logo">
        <img src="pics/logo-login.svg" alt="">
    </div>
    <form action="">
        <div class="field">
            <input type="email" placeholder="Email" required>
        </div>
        <div class="field password">
            <input type="password" placeholder="Password" required>
            <div class="eye" v-if="showpssw1" @click="show1">
                <img src="pics/see.svg" alt="">
            </div>
            <div class="eye" v-if="!showpssw1" @click="hide1">
                <img src="pics/unsee.svg" alt="">
            </div>
        </div>
        <div class="forgot">
            <a href="#">Forgot password?</a>
        </div>
        <button type="submit">Log in</button>
    </form>
    <div class="separator-or">
        <div class="line"></div>
        <div class="middle">OR</div>
        <div class="line"></div>
    </div>
</div>
<div class="redirect">
    <div class="container">
        <span>Don't have an account?</span>
        <a href="">Sign Up.</a>
    </div>
</div>


<script>
    var app = new Vue({
        el: '#register',
        data: {
            email: '',
            password: '',
            confirm: '',
            location: '',
            showpssw1: true,
            showpssw2: true,
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
            show1: function() {
                this.showpssw1 = false;
            },
            hide1: function() {
                this.showpssw1 = true;
            },
            show2: function() {
                this.showpssw2 = false;

            },
            hide2: function() {
                this.showpssw2 = true;

            }
        },
        mounted: function () {
            $("#geo").geocomplete();
        }
    })
</script>
@endsection
