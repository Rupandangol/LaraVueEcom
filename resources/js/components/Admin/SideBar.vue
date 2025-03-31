<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useWeatherStore } from '../../store/weather';
import { storeToRefs } from 'pinia';
const router = useRouter();

const getDate = () => {
    const date = new Date();
    return date.toISOString().split('T')[0];
};

const weatherStore = useWeatherStore();
const { weatherData } = storeToRefs(weatherStore);
const { fetchWeather } = weatherStore;

const AdminName = ref(null);
const AdminId = ref(null);
const logout = () => {
    if (!window.confirm('Are you sure?')) {
        return;
    }
    axios.post('/api/V1/admins/logout', [], {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            localStorage.removeItem('admin-token');
            alert(response.data.message);
            router.push('/admin/login');
        }
    }).catch((e) => {
        console.log(e)
    });
}

const getAdminData = async () => {
    await axios.get(`/api/V1/admin-data`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    }).then((response) => {
        AdminName.value = response.data.data.name;
        AdminId.value = response.data.data.id;
    });
}

const orderPlacedNotify = () => {
    window.Echo.channel('order.placed')
        .listen('.order.placed', (e) => {
            console.log(e);
            Swal.fire({
                position: "top-end",
                icon: "warning",
                title: `New Order Placed #${e.order_id} by ${e.ordered_by}`,
                html: `<a href="${e.url}" autofocus>Go to Order</a>`,
                showConfirmButton: false,
                // timer: 1500,
                showCloseButton: true
            });
        })
}

const adminDeletedNotify = () => {
    window.Echo.channel('admin-deleted')
        .listen('.admin.deleted', (e) => {
            console.log('asdfasdfasd', e);
            Swal.fire({
                title: 'Admin got Deleted',
                text: `Admin: ${e.name}, email:${e.email} got deleted by Admin: ${e.deleted_by}`
            })
        })
}

onMounted(async () => {
    await getAdminData();
    orderPlacedNotify();
    adminDeletedNotify();
    fetchWeather();
})
</script>
<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="#" class="brand-link">
            <span class="brand-text font-weight-light">LEcom Admin</span>&nbsp;
            <span>{{ weatherData?.today_data?.temperature }}°</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 ">
                <div class="info d-flex justify-content-between">
                    <a href="/admin/dashboard" class="d-block">{{ AdminName }}</a>
                    <button class="btn btn-dark btn-sm" @click="logout()"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>

            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/users" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/admins" class="nav-link">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Admin
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/categories" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Categories
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/products" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Products
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/orders" class="nav-link">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p>
                                Orders
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link :to="`/admin/daily-schedule/${getDate()}`" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Daily planner
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="`/admin/todo-list`" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Todo list
                            </p>
                        </router-link>
                    </li>
                </ul>
            </nav>

        </div>

    </aside>
</template>