<template>
    <div style="display: inline-block">
        <div class="tag fast"
             @click="tagClick(tag)"
             v-bind:class="{tada : tag.is_upvoted, animated : hasBeenClicked}">
            <i class="far fa-thumbs-up" v-bind:class="{upvoted : tag.is_upvoted}"></i>
            <span class="tag-name">{{ tag.tag }}</span>
            <span v-if="tag.count" class="tag-count">{{ tag.count }}</span>
        </div>

        <div style="position: fixed; right: 0; bottom: 0;" v-if="tag.is_upvoted">
            <div class="card tag-endorsement-card max-width-small">
                <div class="card--inner">
                    <p class="font-70">Share some love on Kalen's profile related to the <b>{{ tag.tag }}</b> tag you just upvoted</p>
                    <div class="mb-1">
                        <input ref="endorsement" rows="2" placeholder="Share some love" class="text font-70">
                    </div>
                    <div>
                        <a class="btn">Save</a>
                    </div>
                </div>
            </div>
        </div>
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
                    console.log(this.$refs.endorsement) ;
                    ///this.$refs.endorsement.$el.focus();
                }
                tag.is_upvoted = ! tag.is_upvoted;
            }
        }
    }
</script>
