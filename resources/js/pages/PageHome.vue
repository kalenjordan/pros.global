<template>
    <div class="page-home">
        <div class="header text-center max-w-lg mx-auto mt-8 mb-4">
            <div class="">
                <i class="fas fa-bolt font-200 mb-4"></i>
            </div>
            <h1>
                Meet a co-founder, advisor, or team member&mdash;or connect with other
                like-minded founders
            </h1>
        </div>
        <div class="section max-w-3xl mx-auto">
            <!-- <h2 class="mb-1">Founders that are interested in advising:</h2> -->
            <card-group class="mb-8"></card-group>
            <div class="centered">
                <p class="mb-0">
                    <router-link :to="{name: 'search'}">See more founders</router-link>
                </p>
            </div>
        </div>
        <hr/>
        <div class="section centered pb-2">
            <h2 class="mb-4">
                Discover by tag
            </h2>
            <div class="tag-cards mx-auto max-w-lg mb-8 flex">
                <router-link class="no-link flex-1" v-for="tag in tags" :to="{ name: 'tag', params: { slug: tag.slug }}">
                    <div class="card hoverable tag-card">
                        <div class="card--inner">
                            <div class="bold">{{ tag.name }}</div>
                            <div class="font-70">{{ tag.count }} people</div>
                            <div class="mt-1">
                                {{ tag.description }}
                            </div>
                            <div class="mt-1">
                                <img v-for="user in tag.users" v-bind:src="user.avatar_path">
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
            <div class="text-center">
                <p class="mb-0">
                    <router-link :to="{name: 'tags'}">See more tags</router-link>
                </p>
            </div>
        </div>
        <div style="display: none;" v-shortkey="['h']" @shortkey="showKeyboardShortcuts()"></div>
        <modal name="help" >
            <h2 class="mb-2">Keyboard shortcuts</h2>
            <h3 class="mb-1">General</h3>
            <ul class="mb-2">
                <li><strong>h</strong> Show keyboard shortcuts</li>
                <li><strong>Escape</strong> Cancel editing</li>
            </ul>
            <h3 class="mb-1">Profile page</h3>
            <ul class="mb-2">
                <li><strong>e</strong> Edit profile on profile page</li>
                <li><strong>Escape</strong> Cancel editing</li>
            </ul>
            <h3 class="mb-1">Search</h3>
            <ul>
                <li>TBD</li>
            </ul>
        </modal>
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
            axios.get('/api/v1/tags?limit=3').then(function(response) {
                self.tags = response.data;
            });
        },
        methods: {
            showKeyboardShortcuts() {
                this.$modal.show('help');
            }
        }
    }
</script>
