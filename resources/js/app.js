/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Toasted from 'vue-toasted';
import Vuetify from 'vuetify'
import VModal from 'vue-js-modal'
import Vuex from 'vuex'
import Meta from 'vue-meta'
import VueClipboard from 'vue-clipboard2'
import Chat from 'vue-beautiful-chat'
import InstantSearch from 'vue-instantsearch';

Vue.use(Vuetify, {iconfont: 'fa'});
Vue.use(VModal);
Vue.use(Vuex);
Vue.use(VModal);
Vue.use(Meta);

VueClipboard.config.autoSetContainer = true;
Vue.use(VueClipboard);

Vue.use(Toasted, {duration: 5000, position: 'bottom-right'});

Vue.use(Chat);
Vue.use(InstantSearch);

Vue.use(require('vue-shortkey'));
Vue.use(require('vue-moment'));
Vue.use(require('vue-cookies'));

// Event (singular) conflicts with vue-shortkey
window.Events = new window.Vue();

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.config.productionTip = false;
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let store = new Vuex.Store({
    state: {
        user: {
            name: "Kalen"
        },
        presentUsers: [],
        notifications: [],
        unreadNotificationCount: 0,
    },
    getters: {
        // Compute derived state based on the current state. More like computed property.
    },
    mutations: {
        updateUser(state, user) {
            state.user = user
        },
        updatePresentUsers(state, users) {
            state.presentUsers = users
        },
        updateNotifications(state, notifications) {
            state.notifications = notifications
        },
        updateUnreadNotificationCount(state, count) {
            state.unreadNotificationCount = count
        },
    },
    actions: {
        // Get data from server and send that to mutations to mutate the current state
    }
});

const app = new Vue({
    el: '#app',
    data() {
        return typeof(pageData) !== 'undefined' ? pageData : {};
    },
    mounted() {
        if (typeof(pageMounted) === 'function') {
            pageMounted(this);
        }
    },
    methods: typeof(pageMethods) !== 'undefined' ? pageMethods : {},
    computed: typeof(pageComputed) !== 'undefined' ? pageComputed : {},
    store
});