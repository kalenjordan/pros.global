<?php
/** @var $user \App\User */
?>

<div class="card user-card">
    <div class="card--background bg-primary"></div>
    <div class="card--inner p-4">
        <div class="card--avatar">
            <router-link class="relative" :to="{ path: '/' + user.username }">
                <img src="{{ $user->avatar_path }}">
                <i v-if="this.isPresent(user)" class="absolute is-present fas fa-circle"></i>
            </router-link>
        </div>
        <div class="card--cta">
            <router-link class="btn px-5 py-2" :to="{ path: '/' + user.username }">
                View
            </router-link>
        </div>
        <div class="card--identity mb-2">
            <div class="card--identity--name">
                <router-link :to="{ path: '/' + user.username }" class="naked-link">
                    {{ $user->name }}
                </router-link>
            </div>
            <div class="card--identity--handle font-small">
                <router-link :to="{ path: '/' + user.username }" class="naked-link">
                    @{{ user.username }}
                </router-link>
            </div>
        </div>
        <div class="card--about text-xs mb-2 leading-tight" style="-webkit-box-orient: vertical">
            {{ $user->headline }}
        </div>
        {{--<div class="card--tags text-xs">--}}
            {{--<router-link :to="{ path: '/tag/' + tag.slug }" v-for="tag in user.tags" :key="tag.id">--}}
                {{--<div class="tag mini-tag animated fast">--}}
                        {{--<span class="tag-name">--}}
                            {{--<template v-if="tag.icon"><i class="tag-icon material-icons">{{ tag.icon }}</i></template>--}}
                            {{--{{ tag.name }}--}}
                        {{--</span>--}}
                    {{--<span v-if="tag.upvote_count" class="separator">&nbsp;</span>--}}
                    {{--<span v-if="tag.upvote_count" class="count-and-upvote">--}}
                            {{--<span class="tag-count">{{ tag.upvote_count }}</span>--}}
                        {{--</span>--}}
                {{--</div>--}}
            {{--</router-link>--}}
        {{--</div>--}}
    </div>
</div>

<script>
    export default {
        props: ['user'],
        methods: {
            isPresent(user) {
                let ids = [];
                let presentUsers = this.presentUsers;
                for (let i in presentUsers) {
                    ids.push(presentUsers[i].id);
                }
                return ids.includes(user.id);
            }
        },
        computed: {
            loggedInUser() {
                return this.$store.state.user;
            },
            presentUsers() {
                return this.$store.state.presentUsers;
            },
        },
    }
</script>
