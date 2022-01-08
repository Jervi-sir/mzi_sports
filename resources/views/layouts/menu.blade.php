<div id="mobile-menu" class="menu">
    <a href="{{ route('home')}}">
        <img src="../pics/home.svg" alt="">
    </a>
    <a href="{{ route('post.add') }}">
        <img src="../pics/add.svg" alt="">
    </a>
    <a href="#" @click.prevent="settings">
        <img src="../pics/user.svg" alt="">
    </a>
</div>
