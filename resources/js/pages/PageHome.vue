<template>
    <div class="page-home">
        <div class="nav flex pt-8 pl-8 pr-8">
            <div class="logo-wrapper flex-1 text-left">
                <i class="fas fa-bolt font-200 mb-4"></i>
            </div>
            <div class="right-nav flex-1 text-right">
                <input ref="search"
                       class="text w-32 p-2"
                       v-bind:class="{'w-64' : isSearching}"
                       placeholder="Search"
                       v-shortkey="['/']"
                       @shortkey="focusSearch()"
                       @focus="isSearching=1"
                       @blur="isSearching=0"
                >
                <a class="btn px-5 py-2">Sign up</a>
            </div>
        </div>
        <div class="header text-center max-w-lg mx-auto mb-4">
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
        <!--<hr/>-->
        <!--<div class="section centered pb-2">-->
            <!--<h2 class="mb-4">-->
                <!--Discover by tag-->
            <!--</h2>-->
            <!--<div class="tag-cards mx-auto max-w-lg mb-8 flex">-->
                <!--<router-link class="no-link flex-1" v-for="tag in tags" :to="{ name: 'tag', params: { slug: tag.slug }}">-->
                    <!--<div class="card hoverable tag-card">-->
                        <!--<div class="card&#45;&#45;inner">-->
                            <!--<div class="bold">{{ tag.name }}</div>-->
                            <!--<div class="font-70">{{ tag.count }} people</div>-->
                            <!--<div class="mt-1">-->
                                <!--{{ tag.description }}-->
                            <!--</div>-->
                            <!--<div class="mt-1">-->
                                <!--<img v-for="user in tag.users" v-bind:src="user.avatar_path">-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</router-link>-->
            <!--</div>-->
            <!--<div class="text-center">-->
                <!--<p class="mb-0">-->
                    <!--<router-link :to="{name: 'tags'}">See more tags</router-link>-->
                <!--</p>-->
            <!--</div>-->
        <!--</div>-->
        <div style="display: none;" v-shortkey="['h']" @shortkey="showKeyboardShortcuts()"></div>
        <modal name="help" >
            <h3 class="mb-2">Keyboard shortcuts</h3>
            <h4 class="mb-1">General</h4>
            <ul class="mb-4 ml-0 pl-4 leading-normal">
                <li><strong>h</strong> - Show keyboard shortcuts</li>
                <li><strong>Escape</strong> - Cancel editing</li>
                <li><strong>/</strong> - Search</li>
            </ul>
            <h4 class="mb-1">Profile page</h4>
            <ul class="ml-0 pl-4 leading-normal">
                <li><strong>e</strong> - Edit profile on profile page</li>
                <li><strong>Escape</strong> - Cancel editing</li>
            </ul>
            <!--<h3 class="mb-4 ml-0 pl-4 leading-normal">Search</h3>-->
            <!--<ul>-->
                <!--<li>TBD</li>-->
            <!--</ul>-->
        </modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tags: [],
                isSearching: false
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
            },
            focusSearch() {
                this.isSearching = true;
                this.$refs.search.focus();
            }
        }
    }
</script>
