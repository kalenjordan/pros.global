import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageProfile from './pages/PageProfile';
import TagPage from './pages/PageTag';

let routes = [
    {
        name: 'home',
        path: '/',
        component: PageHome
    },
    {
        name: 'tag',
        path: '/tag/:slug',
        component: TagPage
    },
    {
        name: 'profile',
        path: '/:username',
        component: PageProfile
    }
];

export default new VueRouter({
    mode: 'history',
    routes
});