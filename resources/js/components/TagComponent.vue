<template>
    <div class="inline-block">
        <template v-if="editing">
            <div class="tag tag-editable">
                <span class="tag-name">{{ tag.name }}</span>
                <span v-if="tag.count" class="tag-count">{{ tag.count }}</span>
                <i class="material-icons close" @click="deleteTag(tag)">close</i>
            </div>
        </template>
        <template v-else>
            <div class="tag tag-with-upvote border-1 fast text-sm sm:text-base">
                <router-link class="tag-name animated" :to="{ path: '/tag/' + tag.slug }">
                    <template v-if="tag.icon"><i class="tag-icon material-icons">{{ tag.icon }}</i></template>
                    {{ tag.name }}
                </router-link>
                <span class="separator">&nbsp;</span>
                <span class="count-and-upvote animated" @click="upvoteTag(tag)">
                <span class="tag-count" v-if="tag.upvote_count">{{ tag.upvote_count }}</span>
                <i class="material-icons upvote-icon" :class="{upvoted : tag.is_upvoted_by_me}">thumb_up</i>
            </span>

            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['user', 'tag', 'editing'],
        methods: {
            upvoteTag(tag) {
                if (! this.loggedInUser.id) {
                    return alert('Please login before you can upvote someone');
                }

                tag.upvote_count = (tag.is_upvoted_by_me) ? (tag.upvote_count - 1) : (tag.upvote_count + 1);
                tag.is_upvoted_by_me = ! tag.is_upvoted_by_me;
                let username = this.user.username;

                axios.get(this.api('users/' + username + '/upvote-tag/' + tag.id)).then((response) => {
                    let upvote = response.data.upvote;
                    let allUpvotes = response.data.all_upvotes;

                    if (upvote.is_deleted) {
                        this.$root.$emit('upvote-removed', upvote, allUpvotes);
                    } else {
                        console.log('emit added');
                        this.$root.$emit('upvote-added', upvote, allUpvotes);
                    }
                });
            },
            deleteTag(tag) {
                let index = this.user.tags.indexOf(tag);
                this.$delete(this.user.tags, index);

                axios.get(this.api('users/' + this.user.username + '/delete-tag/' + tag.id)).then((response) => {
                    this.user.tags = response.data;
                });
            },
            api(path) {
                path = '/api/v1/' + path;
                if (this.loggedInUser) {
                    path = path + (path.indexOf('?') !== -1 ? '&' : '?') + 'api_token=' + this.loggedInUser.api_token;
                }

                return path;
            },
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            }
        }
    }
</script>
