import VueRouter from 'vue-router';

import Home from './pages/Home';
import UserProfile from './pages/UserProfile';

let routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'profile',
        path: '/:username',
        component: UserProfile
    }
];

export default new VueRouter({
    routes
});