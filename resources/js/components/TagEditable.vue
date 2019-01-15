<template>
    <div class="tag tag-editable">
        <span class="tag-name">{{ tag.name }}</span>
        <span v-if="tag.count" class="tag-count">{{ tag.count }}</span>
        <div class="inline-block ml-1" @click="tagDelete(tag)">
            <i class="fas fa-times close"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tag'],
        mounted() {
            // nada
        },
        methods: {
            tagDelete(tag) {
                let index = this.$parent.user.tags.indexOf(tag);
                // this.$delete(this.$parent.user.tags, index);
                let username = this.$parent.user.username;
                let self = this;

                axios.get('/api/v1/users/' + username + '/delete-tag/' + tag.slug).then(function(response) {
                    self.$parent.user.tags = response.data;
                });
            }
        }
    }
</script>
