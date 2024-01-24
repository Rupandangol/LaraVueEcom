import Dashboard from './pages/Dashboard.vue';
import About from './pages/About.vue';
import Login from './pages/Login.vue';
import Details from './pages/Details.vue';
export default [
    {
        path: '/dashboard',
        name: 'user.dashboard',
        component: Dashboard,
    },
    {
        path: '/about',
        name: 'user.about',
        component: About,
    },
    {
        path: '/product/detail/:id',
        name: 'user-detail',
        component: Details,
    },
    {
        path: '/login',
        name: 'user-login',
        component: Login,
    }
]