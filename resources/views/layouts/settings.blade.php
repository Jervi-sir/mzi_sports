<div id="mobile-setting" class="settings">
    <div class="header">
        <a href="" class="back" @click.prevent="closeSettings"><img src="../pics/arrow-back.svg" alt=""></a>
        <h2>Settings</h2>
        <div class="back"></div>
    </div>
    <div class="main">
        @guest
        <div class="logins">
            <a href="{{ route('login') }}" class="login">Login</a>
            <a href="{{ route('register') }}" class="register">Register</a>
        </div>
        @endguest
        @auth
        <div class="profile">
            <div class="profile-image">
                <img :src="auth.pic" alt="">
            </div>
            <div class="details">
                <h3>@{{ auth.name }}</h3>
                <span>@{{ auth.email }}</span>
            </div>
        </div>
        @endauth
        <div class="setting-links">
            @auth
            <a href="{{ route('profile.mine')}}" class="link">
                <img src="../pics/profile.svg" alt="">
                <span>My Profile</span>
            </a>
            @endauth
            <a href="#" class="link">
                <img src="../pics/faq.svg" alt="">
                <span>FAQ</span>
            </a>
            <a href="#" class="link">
                <img src="../pics/info.svg" alt="">
                <span>About App</span>
            </a>
            @auth
            <form class="link" method="POST" action="{{ route('logout') }}" >
                @csrf
                <img src="../pics/logout.svg" alt="">
                <button type="submit">Logout</button>
            </form>
            @endauth
        </div>
    </div>
</div>
