import VueRouter from 'vue-router';

import PageHome from './pages/PageHome';
import PageProfile from './pages/PageProfile';
import PageTag from './pages/PageTag';
import PageTagList from './pages/PageTagList';
import PageCity from './pages/PageCity';
import PageCityList from './pages/PageCityList';

let routes = [
    {
        name: 'home',
        path: '/',
        component: PageHome
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
        name: 'cities',
        path: '/cities',
        component: PageCityList
    },
    {
        name: 'city',
        path: '/city/:slug',
        component: PageCity
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