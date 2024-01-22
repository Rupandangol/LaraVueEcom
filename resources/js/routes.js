import Dashboard from './pages/Dashboard.vue';
import About from './pages/About.vue';
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
    }
]