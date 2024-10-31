<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, reactive, ref } from 'vue';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import Swal from 'sweetalert2';
import Placeholder from '../../../components/Placeholder.vue';

const users = ref([]);
const msg = ref('');
const errors = ref('');
const loading = ref(true);
const param = reactive({
    'sort': {
        'sort_field': '',
        'sort_direction': 'desc'
    },
    'search': {
        'name': '',
        'email': '',
    }
});
const deleteUser = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            msg.value = '';
            const adminToken = localStorage.getItem('admin-token');
            axios.delete(`/api/V1/users/${id}`, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            }).then((response) => {
                if (response.data.status == 'success') {
                    msg.value = response.data.message;
                }
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
                getUsers();
            })

        }
    });
}

const getUsers = async () => {
    users.value = [];
    loading.value = true;
    const response = await axios.get('/api/V1/users', {
        params: {
            ...param.search,
            ...param.sort
        }
    });
    if (response.status == 200) {
        loading.value = false;
        users.value = response.data.data;
    }
}

const sort = (field) => {
    if (param.sort.sort_field == field && param.sort.sort_direction == 'desc') {
        param.sort.sort_direction = 'asc';
    } else {
        param.sort.sort_direction = 'desc';
    }
    param.sort.sort_field = field;
    getUsers();
}
const clearForm = () => {
    param.search.name = '';
    param.search.email = '';
    getUsers();
}

const toggleUserLock = (userId, lockStatus) => {

    const text = lockStatus ? 'You will be unlocking User access.' : 'You will be Locking User access';
    const confirmButtonText = lockStatus ? 'Unlock!' : 'Lock!';
    Swal.fire({
        title: "Are you sure?",
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: confirmButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            msg.value = '';
            const adminToken = localStorage.getItem('admin-token');
            axios.patch('/api/V1/users/toggle-lock', { 'user_id': userId }, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            })
                .then((res) => {
                    if (res.data.status == 'success') {
                        const iconType = res.data.data.lock === 0 ? 'success' : 'warning'
                        Swal.fire({
                            title: res.data.message,
                            icon: iconType,
                        });
                        getUsers();
                    }
                })

        }
    });
}
onMounted(() => {
    getUsers();
})
</script>
<template>
    <AdminLayout>
        <ErrorMsg :msg="msg" :errors="errors" />
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Users</h3>
            <router-link class="btn btn-success" to="/admin/users/create"><i class=" fa fa-plus"></i><i
                    class="fa fa-user"></i></router-link>
        </div>

        <div class="container d-flex justify-content-end">
            <p>
                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-search"></i>
                </a>
            </p>

        </div>
        <div class="collapse container" id="collapseExample">
            <div class="mb-5">
                <form class="row" @submit.prevent="getUsers()">
                    <div class="form-group col-md-5">
                        <input type="text" v-model="param.search.name" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="text" v-model="param.search.email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Search</button> &nbsp;
                        <button type="reset" @click="clearForm()" class="btn btn-default"><i
                                class="fas fa-sync"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th v-if="param.sort.sort_field == 'name' && param.sort.sort_direction == 'asc'"
                            @click="sort('name')">Username <i class="fa fa-arrow-up"></i></th>
                        <th v-else-if="param.sort.sort_field == 'name' && param.sort.sort_direction == 'desc'"
                            @click="sort('name')">Username <i class="fa fa-arrow-down"></i></th>
                        <th v-else @click="sort('name')">Username</th>
                        <th v-if="param.sort.sort_field == 'email' && param.sort.sort_direction == 'asc'"
                            @click="sort('email')">Email <i class="fa fa-arrow-up"></i></th>
                        <th v-else-if="param.sort.sort_field == 'email' && param.sort.sort_direction == 'desc'"
                            @click="sort('email')">Email <i class="fa fa-arrow-down"></i></th>
                        <th v-else @click="sort('email')">Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading" class="placeholder-glow">
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                    </tr>
                    <tr v-else v-for="(user, index) in users">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user?.name }}</td>
                        <td>{{ user?.email }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-users-edit', params: { id: user.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteUser(user?.id)" class="btn btn-danger btn-sm mr-2"><i
                                    class="fa fa-trash-alt"></i></button>
                            <button @click="toggleUserLock(user?.id, user?.lock)"
                                :class="['btn btn-sm', user?.lock === 1 ? 'btn-danger' : 'btn-success']">
                                <i :class="['fa', user?.lock === 1 ? 'fa-lock' : 'fa-unlock']"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>