import Dashboard from './pages/Dashboard.vue';
import About from './pages/About.vue';
import Login from './pages/Login.vue';
import Details from './pages/Details.vue';
import Checkout from './pages/Checkout/index.vue';
import AdminLogin from './pages/Admin/Login/index.vue';
import AdminDashboard from './pages/Admin/Dashboard/index.vue';
import AdminUserManagement from './pages/Admin/UserManagement/index.vue';
import AdminManagement from './pages/Admin/AdminManagement/index.vue';
import Categories from './pages/Admin/Categories/index.vue';
import Products from './pages/Admin/Products/index.vue';
import Orders from './pages/Admin/Orders/index.vue';

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
    },
    {
        path: '/checkout',
        name: 'user-checkout',
        component: Checkout,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/login',
        name: 'admin-login',
        component: AdminLogin,
    },
    {
        path: '/admin/dashboard',
        name: 'admin-dashboard',
        component: AdminDashboard,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/users',
        name: 'admin-users',
        component: AdminUserManagement,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/admins',
        name: 'admin-admins',
        component: AdminManagement,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/categories',
        name: 'admin-categories',
        component: Categories,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/products',
        name: 'admin-products',
        component: Products,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/orders',
        name: 'admin-orders',
        component: Orders,
        meta: { requiresAdminAuth: true }
    },
]