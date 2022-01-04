<div class="result">
    <div href="#" class="card" v-for="(post, index) in posts">
        <a :href="post.url">
            <img class="card-img" :src="post.media" alt="">
        </a>
        <div class="stats">
            <a href="#" class="heart">
                <img src="../pics/heart.svg" alt="">
            </a>
        </div>
    </div>
</div>
