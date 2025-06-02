import Dashboard from './pages/Dashboard.vue';
import About from './pages/About.vue';
import Login from './pages/Login.vue';
import Details from './pages/Details.vue';
import Checkout from './pages/Checkout/index.vue';
import Profile from './pages/Profile.vue';
import PaymentInfo from './pages/PaymentInfo.vue';
import MyOrder from './pages/MyOrder.vue';
import MyOrderDetail from './pages/MyOrderDetail.vue';
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
import OrderCreate from './pages/Admin/Orders/create.vue';
import DailyPlannerIndex from './pages/Admin/DailyPlanner/index.vue';
import DailyPlannerCalenderIndex from './pages/Admin/CalenderPage/index.vue';
import DailyPlannerAnalytics from './pages/Admin/DailyPlanner/analytics.vue';
import TodoListIndex from './pages/Admin/TodoList/index.vue';
import TodoListArchiveIndex from './pages/Admin/TodoList/archive.vue';
import Chat from './pages/Chat.vue';
import StripeSuccess from './pages/Success.vue';
import StripeCancel from './pages/Cancel.vue';
import TransactionAnalytics from './pages/Admin/Transaction/index.vue';

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
        path: '/public-chat',
        name: 'public.chat',
        component: Chat,
    },
    {
        path: '/product/detail/:id',
        name: 'product-detail',
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
        path: '/profile',
        name: 'user-profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/payment-info',
        name: 'payment-info',
        component: PaymentInfo,
        meta: { requiresAuth: true }
    },
    {
        path: '/my-order',
        name: 'user-my-order',
        component: MyOrder,
        meta: { requiresAuth: true }
    },
    {
        path: '/my-order/detail/:id',
        name: 'user-my-order-detail',
        component: MyOrderDetail,
        props: true,
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
        path: '/admin/orders/create',
        name: 'admin-order-create',
        component: OrderCreate,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/orders/:id',
        name: 'admin-order-edit',
        component: OrderEdit,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/daily-schedule-calender',
        name: 'admin-daily-schedule-calender',
        component: DailyPlannerCalenderIndex,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/daily-schedule/:date?',
        name: 'admin-daily-schedule',
        component: DailyPlannerIndex,
        props: true,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/daily-schedule-analytics',
        name: 'admin-daily-schedule-analytics',
        component: DailyPlannerAnalytics,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/todo-list',
        name: 'admin-todo-list',
        component: TodoListIndex,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/admin/todo-list-archive',
        name: 'admin-todo-list-archive',
        component: TodoListArchiveIndex,
        meta: { requiresAdminAuth: true }
    },
    {
        path: '/stripe/success',
        name: 'stripe-success',
        component: StripeSuccess,
    },
    {
        path: '/stripe/cancel',
        name: 'stripe-cancel',
        component: StripeCancel,
    },
    {
        path: '/admin/transaction/analytics',
        name: 'admin-transaction-analytics',
        component: TransactionAnalytics,
    },

]