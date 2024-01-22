import './bootstrap';

import { createApp } from 'vue';

import {createRouter, createWebHistory} from 'vue-router';
import Routes from './routes.js';
import App from './App.vue';
const app=createApp(App);

const router=createRouter({
    routes:Routes,
    history:createWebHistory(),
})
app.use(router);
app.mount('#app');