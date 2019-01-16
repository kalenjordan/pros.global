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
        props: ['user', 'tag'],
        mounted() {
            // nada
        },
        methods: {
            tagDelete(tag) {
                let index = this.user.tags.indexOf(tag);
                this.$delete(this.user.tags, index);

                axios.get('/api/v1/users/' + this.user.username + '/delete-tag/' + tag.id).then((response) => {
                    this.user.tags = response.data;
                });
            }
        }
    }
</script>
