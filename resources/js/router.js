import Vue from 'vue';
import Router from 'vue-router';
import Home from './pages/index.vue';
import Book from './pages/book.vue';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
        },
        {
            path: '/book',
            name: 'book',
            component: Book
        },
    ],
    scrollBehavior() {
        return {
            x: 0,
            y: 0
        }
    }
});

export default router;

