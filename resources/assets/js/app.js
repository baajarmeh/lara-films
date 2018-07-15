/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('film-item', require('./components/Film.vue'));
Vue.component('film-collection', require('./components/Films.vue'));
Vue.component('star-rating', require('./components/Stars.vue'));
Vue.component('comment-item', require('./components/Comments.vue'));

const app = new Vue({
    el: '#app',
    data: {
        token: GLOBAL.token
    }
});
