@extends('layouts.rootlogs')

@section('title')
<title></title>
@endsection

@section('style-header')
<link rel="stylesheet" href="css/logs.css">
@endsection

@section('body')
<div class="form">
    <div class="logo">
        <img src="pics/logo-login.svg" alt="">
    </div>
    <form action="">
        <div class="field">
            <input type="email" placeholder="Email">
        </div>
        <div class="field password">
            <input type="password" placeholder="Password">
            <div class="eye">
                <img src="pics/see.svg" alt="">
            </div>
        </div>
        <div class="field password">
            <input type="text" placeholder="choose your Nation">
            <div class="eye">
                <img src="pics/arrow.svg" alt="">
            </div>
        </div>
        <button class="register" type="submit">Sign Up</button>
    </form>
</div>

<div class="redirect">
    <div class="container">
        <span>Already have an account?</span>
        <a href="">Log In.</a>
    </div>
</div>
@endsection
