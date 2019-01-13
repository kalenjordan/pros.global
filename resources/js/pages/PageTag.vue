<template>
    <div class="container page page-tag">
        <div class="header centered pb-1">
            <router-link class="naked-link" to="/"><i class="fas fa-bolt font-200"></i></router-link>
            <h1 class="mt-2">
                {{ tag.tag }}
            </h1>
        </div>
        <div class="section margin-auto max-width-medium">
            <div class="card">
                <div class="card--inner text-left">
                    {{ tag.description }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tag: {}
            }
        },
        mounted() {
            let self = this;
            axios.get('/api/v1/tags/1').then(function(response) {
                self.tag = response.data;
            });
        }
    }
</script>
