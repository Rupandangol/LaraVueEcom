<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
const router = useRouter();
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
</script>
<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="index3.html" class="brand-link">
            <span class="brand-text font-weight-light">LEcom Admin</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 ">
                <div class="info d-flex justify-content-between">
                    <a href="#" class="d-block">Alexander Pierce</a>
                    <button class="btn btn-dark btn-sm" @click="logout()"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>

            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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

                </ul>
            </nav>

        </div>

    </aside>
</template>