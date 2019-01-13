<template>
    <div class="page-home">
        <div class="header">
            <div class="centered">
                <i class="fas fa-bolt font-200 mb-2"></i>
            </div>
            <h1>
                Meet a co-founder, advisor, or team member&mdash;or connect with other
                like-minded founders
            </h1>
        </div>
        <div class="section centered pb-2">
            <!-- <h2 class="mb-1">Founders that are interested in advising:</h2> -->
            <card-group></card-group>
        </div>
        <div class="section centered pb-2">
            <h2 class="mb-1">
                Discover by tag
            </h2>
            <div class="tag-cards">
                <router-link class="no-link" v-for="tag in tags" :to="{ name: 'tag', params: { slug: tag.slug }}">
                    <div class="card hoverable tag-card">
                        <div class="card--inner">
                            <div class="bold">{{ tag.tag }}</div>
                            <div class="font-70">{{ tag.count }} people</div>
                            <div class="mt-1">
                                {{ tag.description }}
                            </div>
                            <div class="mt-1">
                                <img v-for="user in tag.users" v-bind:src="user.avatar_url">
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tags: []
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/tags').then(function(response) {
                self.tags = response.data;
            });
        }
    }
</script>
