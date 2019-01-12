import VueRouter from 'vue-router';

import Home from './pages/Home';
import User from './pages/User';

let routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/user/:id',
        component: User
    }
];

export default new VueRouter({
    routes
});