import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageSearch from './pages/PageSearch';
import PageUserProfile from './pages/PageUserProfile';
import PageTag from './pages/PageTag';
import PageTagList from './pages/PageTagList';
import PageUpvote from './pages/PageUpvote';

let routes = [
    {
        name: 'home',
        path: '/',
        component: PageHome
    },
    {
        name: 'search',
        path: '/search',
        component: PageSearch
    },
    {
        name: 'search-query',
        path: '/search/:query',
        component: PageSearch
    },
    {
        name: 'tags',
        path: '/tags',
        component: PageTagList
    },
    {
        name: 'tag',
        path: '/tag/:slug',
        component: PageTag
    },
    {
        name: 'upvote',
        path: '/upvotes/:id',
        component: PageUpvote
    },
    {
        name: 'profile',
        path: '/:username',
        component: PageUserProfile
    }
];

export default new VueRouter({
    mode: 'history',
    routes
});