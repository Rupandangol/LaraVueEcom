<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import Swal from 'sweetalert2';

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