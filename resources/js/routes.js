import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageProfile from './pages/PageProfile';

let routes = [
    {
        name: 'home',
        path: '/',
        component: PageHome
    },
    {
        name: 'profile',
        path: '/:username',
        component: PageProfile
    }
];

export default new VueRouter({
    routes
});