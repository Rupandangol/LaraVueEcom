<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const users = ref([]);
const msg = ref('');
const errors = ref('');
const getUsers = () => {
    axios.get('/api/V1/users')
        .then((response) => {
            users.value = response.data.data;
        })
}

const deleteUser = (id) => {
    msg.value = '';
    if (!window.confirm('you sure?')) {
        return;
    }
    const adminToken = localStorage.getItem('admin-token');
    axios.delete(`/api/V1/users/${id}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            msg.value = response.data.message;
        }
        alert('Deleted sucessfully');
        getUsers();
    })
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
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users">
                        <td>{{ index + 1 }}</td>
                        <td>{{ user?.name }}</td>
                        <td>{{ user?.email }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-users-edit', params: { id: user.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteUser(user?.id)" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>