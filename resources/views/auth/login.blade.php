@extends('layouts.rootlogs')

@section('title')
<title>login to mzisports</title>
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
    <div class="content">
        <div class="logo">
            <img src="pics/logo-login.svg" alt="">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field @error('email') is-invalid @enderror">
                <input type="email" name="email" placeholder="{{__('Email')}}">
                @error('email')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="field password @error('password') is-invalid @enderror">
                <input :type="showpssw1 ? 'password' : 'text'" name="password" placeholder="{{__('Password')}}">
                <div class="eye" v-if="showpssw1" @click="show1">
                    <img src="pics/unsee.svg" alt="">
                </div>
                <div class="eye" v-if="!showpssw1" @click="hide1">
                    <img src="pics/see.svg" alt="">
                </div>
                @error('password')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- Remember Me -->
            <div class="extra-options">
                <label for="remember_me" class="remember-me">
                    <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                    <span>{{ __('Remember me') }}</span>
                </label>
                <div class="forgot">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                </div>
            </div>
            <button type="submit">{{ __('Log in') }}</button>
        </form>
        <div class="separator-or">
            <div class="line"></div>
            <div class="middle">OR</div>
            <div class="line"></div>
        </div>
    </div>
</div>
<div class="redirect">
    <div class="container">
        <span>Don't have an account?</span>
        <a href="{{ route('register') }}">Sign Up.</a>
    </div>
</div>


<script>
    var app = new Vue({
        el: '#login',
        data: {
            email: '',
            password: '',
            confirm: '',
            location: '',
            showpssw1: true,
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

        },
        mounted: function () {
            $("#geo").geocomplete();
        }
    })
</script>

@endsection


