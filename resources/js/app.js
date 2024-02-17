import './bootstrap';
import 'admin-lte/plugins/jquery/jquery.min.js'; 
import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import VueSweetalert2 from 'vue-sweetalert2';

import { createApp } from 'vue';

import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
import App from './App.vue';

const app = createApp(App);

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
})
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('user-token');

    if (to.matched.some((route) => route.meta.requiresAuth) && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    const isAdminAuthenticated = localStorage.getItem('admin-token');

    if (to.matched.some((route) => route.meta.requiresAdminAuth) && !isAdminAuthenticated) {
        next('/admin/login');
    } else {
        next();
    }
});

app.use(router);
app.use(VueSweetalert2);
app.mount('#app');