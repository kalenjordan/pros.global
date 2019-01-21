import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageSearch from './pages/PageSearch';
import PageUserProfile from './pages/PageUserProfile';
import PageTag from './pages/PageTag';
import PageTagList from './pages/PageTagList';
import PageUpvote from './pages/PageUpvote';
import PageLogout from './pages/PageLogout';

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
        name: 'logout',
        path: '/logout',
        component: PageLogout
    },
    {
        name: 'profile',
        path: '/:username',
        component: PageUserProfile
    }
];

let router = new VueRouter({
    mode: 'history',
    routes
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