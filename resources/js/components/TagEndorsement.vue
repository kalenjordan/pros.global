<template>
    <div ref="wrapper" class="tag-endorsement-wrapper animated fast slideInRight">
        <div class="card tag-endorsement-card max-width-small font-90">
            <div class="card--inner">
                <p class="mb-05">Share some love on Kalen's profile!</p>
                <div class="mb-05">
                    <textarea ref="endorsement" rows="2" placeholder="Share some love" class="text width-100"></textarea>
                </div>
                <div>
                    <a class="btn" @click="saveEndorsement" href="javascript://">Save endorsement</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            let self = this;
            window.Events.$on('tag-added', function(tag) {
                self.$refs.wrapper.style.right = '0';
                self.tag = tag;
                self.$nextTick(function() {
                    self.$refs.endorsement.placeholder = "e.g. Kalen is great with " + tag.name;
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
            }
        }
    }
</script>
