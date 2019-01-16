<template>
    <div ref="wrapper" class="tag-endorsement-wrapper animated fast slideInRight">
        <div class="card tag-endorsement-card max-width-small font-90">
            <div class="card--inner">
                <p class="mb-2">Share some love on {{ upvote.tagged_user_firstname }}'s profile!</p>
                <div class="mb-2">
                    <textarea
                            ref="endorsement" rows="2"
                            placeholder="Share some love" class="text w-full p-2 w-full text-sm"
                            v-shortkey="['meta', 'enter']"
                            @shortkey="saveEndorsement"
                    >
                    </textarea>
                </div>
                <div>
                    <a class="btn px-5 py-2" @click="saveEndorsement" href="javascript://">Share some love</a>
                    <a class="paragraph-link ml-3" @click="closeEndorsement" v-shortkey="['esc']" @shortkey="closeEndorsement">Skip</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        mounted() {
            let self = this;
            window.Events.$on('tag-upvoted', function (upvote) {
                self.$refs.wrapper.style.right = '0';
                self.upvote = upvote;
                self.$nextTick(function () {
                    self.$refs.endorsement.placeholder = "e.g. " + upvote.tagged_user_firstname + " is great with " + upvote.tag_name;
                    self.$refs.endorsement.focus();
                });
            });
            window.Events.$on('upvote-removed', function (upvote) {
                self.$refs.wrapper.style.right = '-500px';
            });
        },
        data() {
            return {
                upvote: {},
                shouldShow: false
            }
        },
        methods: {
            saveEndorsement() {
                this.$refs.wrapper.style.right = '-500px';
                this.$toasted.show('Saved endorsement!', {duration: 5000, position: "bottom-right"});
                let message = this.$refs.endorsement.value;
                let self = this;

                let auth = '?api_token=' + window.api_token;
                axios.post('api/v1/upvotes/' + this.upvote.id + auth, {
                    message: message
                }).then(function(response) {
                    if (self.$parent.user) { // Won't be set on homepage / card view
                        self.$parent.user.upvotes = response.data;
                    }
                });
            },
            closeEndorsement() {
                this.$refs.wrapper.style.right = '-500px';
            }
        }
    }
</script>
