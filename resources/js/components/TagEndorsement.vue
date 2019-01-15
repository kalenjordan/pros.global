<template>
    <div ref="wrapper" class="tag-endorsement-wrapper animated fast slideInRight">
        <div class="card tag-endorsement-card max-width-small font-90">
            <div class="card--inner">
                <p class="mb-2">Share some love on {{ tag.tagged_user_firstname }}'s profile!</p>
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
        mounted() {
            let self = this;
            window.Events.$on('tag-added', function (tag) {
                self.$refs.wrapper.style.right = '0';
                self.tag = tag;
                self.$nextTick(function () {
                    self.$refs.endorsement.placeholder = "e.g. " + tag.tagged_user_firstname + " is great with " + tag.name;
                    self.$refs.endorsement.focus();
                });
            });
        },
        data() {
            return {
                tag: {},
                shouldShow: false
            }
        },
        methods: {
            saveEndorsement() {
                this.$refs.wrapper.style.right = '-500px';
                this.$toasted.show('Saved endorsement!', {duration: 5000, position: "bottom-right"});
            },
            closeEndorsement() {
                this.$refs.wrapper.style.right = '-500px';
            }
        }
    }
</script>
