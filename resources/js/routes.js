import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageSearch from './pages/PageSearch';
import PageUserProfile from './pages/PageUserProfile';
import PageUpvote from './pages/PageUpvote';
import PageSavedSearches from './pages/PageSavedSearches';
import PageSavedSearch from './pages/PageSavedSearch';
import PageLinked from './pages/PageLinked';

let routes = [
    {
        name: 'linkedin-auth',
        path: '/auth/linkedin/callback',
        component: PageLinked,
    },
    {
        name: 'home',
        path: '/',
        component: PageHome
    },
    {
        name: 'saved-searches',
        path: '/saved-searches',
        component: PageSavedSearches
    },
    {
        name: 'saved-search',
        path: '/s/:slug',
        component: PageSavedSearch
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

let router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

router.afterEach(( to, from ) => {
    if ("ga" in window) {
        if (typeof(ga.getAll) == 'function') {
            let tracker = ga.getAll()[0];
            if (tracker) {
                tracker.send("pageview", to.path);
            }
        }
    }
});

export default router;