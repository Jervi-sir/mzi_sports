<div id="mobile-menu" class="menu">
    <a href="{{ route('home')}}">
        <img src="../pics/home_green.svg" alt="">
    </a>
    <a href="{{ route('post.add') }}">
        <img src="../pics/add_green.svg" alt="">
    </a>
    <a href="#" @click.prevent="settings">
        <img src="../pics/user_green.svg" alt="">
    </a>
</div>
