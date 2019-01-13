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
            <div v-for="tag in tags" class="card">
                {{ tag.tag }}
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
