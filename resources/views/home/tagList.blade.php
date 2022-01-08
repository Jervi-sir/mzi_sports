<div id="mobile-tags" class="tags-list">
    <div class="header">
        <a href="" class="back" @click.prevent="closeMoreTags"><img src="../pics/arrow-back.svg" alt=""></a>
        <h2>Tags</h2>
        <div class="back"></div>
    </div>
    <div class="main">
        <span v-for="(tag, index) in allTags">
            <a href="#" :class="{ active: tag.active }">@{{ tag.name }}</a>
        </span>
    </div>
</div>
