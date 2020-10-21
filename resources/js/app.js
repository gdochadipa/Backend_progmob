require('./bootstrap');
import './firebase'

import Vue from 'vue'
import App from './App.vue'
import router from './router';
import { firestorePlugin } from 'vuefire';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-default.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(firestorePlugin);
Vue.use(VueToast);
Vue.use(VueSweetalert2);


new Vue({
    el: '#app',
    router,
    components: {
        'app': App
    }
})
