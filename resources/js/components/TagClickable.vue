<template>
    <div class="tag fast"
         @click="tagClick(tag)"
         v-bind:class="{tada : tag.is_upvoted, animated : hasBeenClicked}">
        <i class="far fa-thumbs-up" v-bind:class="{upvoted : tag.is_upvoted}"></i>
        <span class="tag-name">{{ tag.tag }}</span>
        <span v-if="tag.count" class="tag-count">{{ tag.count }}</span>
    </div>
</template>

<style type="text/css">
    @import './../../../node_modules/animate.css/animate.min.css';
</style>

<script>
    export default {
        props: ['tag'],
        mounted() {
            // nada
        },
        data() {
            return {
                hasBeenClicked: false
            }
        },
        methods: {
            tagClick(tag) {
                this.hasBeenClicked = true;
                if (tag.is_upvoted) {
                    tag.count -= 1;
                } else {
                    tag.count += 1;
                }
                tag.is_upvoted = ! tag.is_upvoted;
            }
        }
    }
</script>
