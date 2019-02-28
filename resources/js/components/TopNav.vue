<template>
    <div>
        <div class="nav flex items-center">
            <div class="logo">
                <a href="/" class="naked-link">
                    <img class="logo w-3rem" src="/images/icon.png">
                </a>
            </div>
            <div class="ml-auto mr-6" v-if="!isSearching" @click="focusSearch()">
                <i class="search material-icons text-gray-dark font-120 cursor-pointer animated">
                    search
                </i>
            </div>
            <slot></slot>
            <div class="mr-6" v-if="!isSearching">
                <notification-bell></notification-bell>
            </div>
            <div v-if="!isSearching">
                <div>
                    <img v-if="loggedIn" class="animate avatar w-10 rounded-full cursor-pointer border-2"
                         @click="showingMenu = !showingMenu"
                         :src="loggedInUser.avatar_path">
                    <a v-else class="btn px-5 py-2" href="/auth/linkedin" target="_blank">Login</a>
                </div>
                <div v-if="showingMenu" class="card logged-in-menu">
                    <div class="card-inner p-3 font-120">
                        <div class="block p-2">
                            <a class="naked-link" v-if="loggedIn" :href="'/' + loggedInUser.username">
                                View Profile
                            </a>
                        </div>
                        <div class="block p-2">
                            <a class="naked-link" v-if="loggedIn" href="/posts/new">
                                New Post
                            </a>
                        </div>
                        <div class="block p-2">
                            <a href="javascript://" class="naked-link" @click="logout">Log out</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ml-auto" v-if="isSearching" @blur="isSearching=0">
                <ais-index :app-id="algoliaAppID" :api-key="algoliaPublicKey" :index-name="algoliaIndexName">
                    <ais-search-box autofocus></ais-search-box>
                    <ais-results>
                        <template slot-scope="{ result }">
                            <a class="ais-result-link no-link" :href="result.url">
                                <img v-if="result.type==='user'" class="w-8 rounded-full" :src="result.avatar_path">
                                <i v-if="result.type==='tag'" class="fas fa-tag"></i>
                                <i v-if="result.type==='saved-search'" class="fas fa-search"></i>
                                <span class="name" style="-webkit-box-orient: vertical;">{{ result.name }}</span>
                            </a>
                        </template>
                    </ais-results>
                    <ais-pagination></ais-pagination>
                </ais-index>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isSearching: false,
                showingMenu: false,
                algoliaAppID: process.env.MIX_ALGOLIA_APP_ID,
                algoliaPublicKey: process.env.MIX_ALGOLIA_PUBLIC_KEY,
                algoliaIndexName: process.env.MIX_ALGOLIA_INDEX_NAME,
            }
        },
        mounted() {
            window.addEventListener('keyup', this.hotkeys);

            if (this.$cookies.get('user')) {
                this.$store.commit('updateUser', this.$cookies.get('user'));
            }

            window.Events.$on('user-authenticated', (data) => {
                this.$cookies.set('user', data);
                window.location.reload();
            });
        },
        methods: {
            focusSearch() {
                this.isSearching = true;
                this.$nextTick(() => {
                    if (this.$refs.search) {
                        //this.$refs.search.focus();
                        // Sometimes the this.$refs.search.focus() doesn't work
                        document.querySelector('.ais-input').focus();
                    }
                });

                // Sometimes the focus() in the $nextTick() above doesn't work
                setTimeout(() => {
                    document.querySelector('.ais-input').focus();
                }, 100);
            },
            hotkeys(e) {
                this.instantSearchHotkeys(e);

                if (e.key === 'Escape') {
                    this.isSearching = false;
                    if (document.querySelector('.ais-input')) document.querySelector('.ais-input').blur();
                }
                if (document.activeElement.id === 'top-nav-search') {
                    if (e.key === 'Enter') {
                        this.search();
                    }
                } else if (document.activeElement.tagName === 'BODY') {
                    if (e.key === '/') {
                        this.focusSearch();
                    }
                }
            },
            instantSearchHotkeys(e) {
                let results = document.querySelector('.ais-results');
                let activeLink = document.querySelector('.ais-result-link.active');

                console.log(document.activeElement.className);
                if (document.activeElement.className === 'ais-input' && ! activeLink) {
                    if (e.key === 'ArrowDown') {
                        results.firstElementChild.classList.add('active');
                    }

                    if (e.key === 'Enter') {
                        results.firstElementChild.click();
                    }
                }

                if (activeLink) {
                    if (e.key === 'Enter') {
                        activeLink.click();
                    } else if (e.key === 'ArrowDown') {
                        if (activeLink.nextElementSibling) {
                            activeLink.classList.remove('active');
                            activeLink.nextElementSibling.classList.add('active');
                        }
                    } else if (e.key === 'ArrowUp') {
                        if (activeLink.previousElementSibling) {
                            activeLink.classList.remove('active');
                            activeLink.previousElementSibling.classList.add('active');
                        }
                    }
                }
            },
            search() {
                this.$router.push({
                    name: 'search-query',
                    params: {query: this.$refs.search.value},
                });
            },
            isAdminViewingProfilePage() {
                return this.loggedInUser.is_admin && this.user && this.user.id;
            },
            isAdmin() {
                return (this.loggedInUser.is_admin || this.loggedInUser.being_impersonated);
            },
            logout() {
                this.showingMenu = false;
                this.$cookies.set('user', null);
                this.$store.commit('updateUser', {});
                this.$toasted.show("You're logged out! Don't be a stranger now, ya hear? 🤠", {duration: 2000});
            },
       },
        computed: {
            loggedIn() {
                return this.$store.state.user && this.$store.state.user.id;
            },
            loggedInUser() {
                return this.$store.state.user;
            },
            canEdit() {
                return true;
            }
        },
    }
</script>
