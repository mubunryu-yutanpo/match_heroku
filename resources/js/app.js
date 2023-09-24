/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('apply-list-component', require('./components/ApplyListComponent.vue').default);
Vue.component('avatar-preview-component', require('./components/AvatarPreviewComponent.vue').default);
Vue.component('detail-component', require('./components/DetailComponent.vue').default);
Vue.component('direct-message-component', require('./components/DirectMessageComponent.vue').default);
Vue.component('direct-message-list-component', require('./components/DirectMessageListComponent.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('list-component', require('./components/ListComponent.vue').default);
Vue.component('mypage-component', require('./components/MypageComponent.vue').default);
Vue.component('post-list-component', require('./components/PostListComponent.vue').default);
Vue.component('public-message-component', require('./components/PublicMessageComponent.vue').default);
Vue.component('public-message-list-component', require('./components/PublicMessageListComponent.vue').default);
Vue.component('text-counter-component', require('./components/TextCounterComponent.vue').default);
Vue.component('thumbnail-preview-component', require('./components/ThumbnailPreviewComponent.vue').default);


const VueAwesomeSwiper = window.VueAwesomeSwiper;
Vue.use(VueAwesomeSwiper);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

// const preview = new Vue({
//     el: '#preview',
// });

const counter = new Vue({
    el: '#counter',
});

