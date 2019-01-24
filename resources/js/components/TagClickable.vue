<template>
    <div style="display: inline-block">
        <div class="tag fast text-sm sm:text-base"
             @click="tagClick(tag)"
             v-bind:class="{'border-gray-lighter' : tag.is_upvoted_by_me, animated : hasBeenClicked, tada: tag.is_upvoted_by_me}">
            <i class="far fa-thumbs-up" v-bind:class="{upvoted : tag.is_upvoted_by_me}"></i>
            <span class="tag-name">{{ tag.name }}</span>
            <span v-if="tag.upvote_count" class="tag-count">{{ tag.upvote_count }}</span>
        </div>
    </div>
</template>

<style type="text/css">
    @import './../../../node_modules/animate.css/animate.min.css';
</style>

<script>
    export default {
        props: ['user', 'tag'],
        data() {
            return {
                hasBeenClicked: false,
            }
        },
        methods: {
            tagClick(tag) {
                if (! this.loggedInUser.id) {
                    return alert('Please login before you can upvote someone');
                }

                this.hasBeenClicked = true;
                if (tag.is_upvoted_by_me) {
                    tag.upvote_count -= 1;
                } else {
                    tag.upvote_count += 1;
                }
                tag.is_upvoted_by_me = ! tag.is_upvoted_by_me;
                let username = this.user.username;

                let auth = '?api_token=' + this.loggedInUser.api_token;
                axios.get('/api/v1/users/' + username + '/upvote-tag/' + tag.id + auth).then(function(response) {
                    let upvote = response.data.upvote;
                    let allUpvotes = response.data.all_upvotes;

                    if (upvote.is_deleted) {
                        window.Events.$emit('upvote-removed', upvote, allUpvotes);
                    } else {
                        window.Events.$emit('upvote-added', upvote, allUpvotes);
                    }
                });
            }
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            }
        }
    }
</script>
