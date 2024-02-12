import Dashboard from './pages/Dashboard.vue';
import About from './pages/About.vue';
import Login from './pages/Login.vue';
import Details from './pages/Details.vue';
import Checkout from './pages/Checkout/index.vue';
import AdminLogin from './pages/Admin/Login/index.vue';
import AdminDashboard from './pages/Admin/Dashboard/index.vue';
import AdminUserManagement from './pages/Admin/UserManagement/index.vue';
import AdminUserManagementCreate from './pages/Admin/UserManagement/create.vue';
import AdminUserManagementEdit from './pages/Admin/UserManagement/edit.vue';
import AdminManagement from './pages/Admin/AdminManagement/index.vue';
import AdminManagementCreate from './pages/Admin/AdminManagement/create.vue';
import AdminManagementEdit from './pages/Admin/AdminManagement/edit.vue';
import Categories from './pages/Admin/Categories/index.vue';
import CategoryCreate from './pages/Admin/Categories/create.vue';
import CategoryEdit from './pages/Admin/Categories/edit.vue';
import Products from './pages/Admin/Products/index.vue';
import ProductCreate from './pages/Admin/Products/create.vue';
import ProductEdit from './pages/Admin/Products/edit.vue';
import Orders from './pages/Admin/Orders/index.vue';
import OrderDetail from './pages/Admin/Orders/detail.vue';
import OrderEdit from './pages/Admin/Orders/edit.vue';


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
        path: '/admin/users/create',
        name: 'admin-users-create',
        component: AdminUserManagementCreate,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/users/edit/:id',
        name: 'admin-users-edit',
        component: AdminUserManagementEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/admins',
        name: 'admin-admins',
        component: AdminManagement,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/admins/create',
        name: 'admin-admins-create',
        component: AdminManagementCreate,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/admins/edit/:id',
        name: 'admin-admins-edit',
        component: AdminManagementEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/categories',
        name: 'admin-categories',
        component: Categories,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/categories/create',
        name: 'admin-categories-create',
        component: CategoryCreate,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/categories/edit/:id',
        name: 'admin-categories-edit',
        component: CategoryEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/products',
        name: 'admin-products',
        component: Products,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/products/create',
        name: 'admin-products-create',
        component: ProductCreate,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/products/edit/:id',
        name: 'admin-products-edit',
        component: ProductEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/orders',
        name: 'admin-orders',
        component: Orders,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/order-details/:id',
        name: 'admin-order-details',
        component: OrderDetail,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/orders/:id',
        name: 'admin-order-edit',
        component: OrderEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
]