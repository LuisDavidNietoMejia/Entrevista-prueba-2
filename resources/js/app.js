

require('./bootstrap');

import Echo from 'laravel-echo'
window.Pusher = require('pusher-js')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ASDASD2121',
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true,
    forceTLS: false
});

window.Vue = require('vue');

import VModal from 'vue-js-modal';
import VueElementLoading from 'vue-element-loading';
import VueSingleSelect from "vue-single-select"

Vue.use(VModal, { dynamic: true, injectModalsContainer: true });
Vue.component('VueElementLoading', VueElementLoading);
Vue.component('vue-single-select', VueSingleSelect);

Vue.component('user-index', require('./components/user/user-index.vue').default);
Vue.component('user-edit', require('./components/user/user-edit.vue').default);
Vue.component('user-store', require('./components/user/user-store.vue').default);

Vue.component('document-index', require('./components/document/document-index.vue').default);
Vue.component('document-edit', require('./components/document/document-edit.vue').default);
Vue.component('document-store', require('./components/document/document-store.vue').default);


const app = new Vue({
    el: '#app',
});
