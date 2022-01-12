@extends('layouts.rootlogs')

@section('title')
<title>join mzisports</title>
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
<div id="register" class="form">
    <div class="content">
        <div class="logo">
            <img src="pics/logo-logs.svg" alt="">
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="field">
                <input type="text" name="name" placeholder="{{__('Name')}}">
            </div>
            <div class="field @error('email') is-invalid @enderror">
                <input v-model="email" type="email" name="email" placeholder="{{__('Email')}}" v-on:keyup="validateForm" required>
                @error('email')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="field password @error('password') is-invalid @enderror">
                <input v-model="password" :type="showpssw1 ? 'password' : 'text'" name="password" v-on:keyup="validateForm" placeholder="{{__('Password')}}" required>
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
            <div class="field password">
                <input v-model="confirm" :type="showpssw2 ? 'password' : 'text'" name="password_confirmation" v-on:keyup="validateForm" placeholder="{{__('Confirm Password')}}" required>
                <div class="eye" v-if="showpssw2" @click="show2">
                    <img src="pics/unsee.svg" alt="">
                </div>
                <div class="eye" v-if="!showpssw2" @click="hide2">
                    <img src="pics/see.svg" alt="">
                </div>
            </div>
            <div class="field password">
                <input id="geo" type="text" name="location" v-model="location" placeholder="choose your Nation" required>
                <div class="eye" >
                    <img src="pics/map.svg" alt="">
                </div>
            </div>
            <div class="already">
                <a class="" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>
            <button class="register-button" :disabled="disabled" type="submit">{{ __('Register') }}</button>
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
        <a href="{{ route('login') }}">Sign Up.</a>
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
            disabled: true,
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

            },
            isEmail: function() {
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)){
                    return true;
                }
                return false;
            },
            pssdMatch: function() {
                if(this.password == this.confirm) {
                    return true;
                }
                return false;
            },
            validateForm: function() {
                if(this.isEmail() && this.pssdMatch()) {
                    console.log(true);
                    this.disabled = false;
                } else {
                    console.log(false);
                    this.disabled = true;
                }

            }
        },
        mounted: function () {
            $("#geo").geocomplete();
        }
    })
</script>

@endsection




