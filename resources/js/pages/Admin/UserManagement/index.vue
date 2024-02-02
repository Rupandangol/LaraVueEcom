<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';

const users = ref([]);
const getUsers = () => {
    axios.get('/api/V1/users')
        .then((response) => {
            users.value = response.data.data;
        })
}
onMounted(() => {
    getUsers();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Users</h3>
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
                            <button class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>