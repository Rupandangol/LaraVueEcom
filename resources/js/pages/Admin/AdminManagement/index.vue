<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const msg = ref('');
const errors = ref('');
const admins = ref([]);
const getAdmins = () => {
    const adminToken = localStorage.getItem('admin-token');
    axios.get('/api/V1/admins', {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        admins.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    })
}

const deleteAdmin = (id) => {
    if (!window.confirm('you sure?')) {
        return;
    }
    msg.value = '';
    const adminToken = localStorage.getItem('admin-token');
    axios.delete(`/api/V1/admins/${id}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            msg.value = response.data.message;
            getAdmins();
        }
    }).catch((e) => {
        console.log(e);
    })
}

onMounted(() => {
    getAdmins();
})
</script>
<template>
    <AdminLayout>
        <ErrorMsg :msg="msg" :errors="''" />
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Admins</h3>
            <router-link to="/admin/admins/create" class="btn btn-success"><i class="fa fa-plus"></i> <i
                    class="fa fa-user-tie"></i></router-link>
        </div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(admin, index) in  admins ">
                        <td>{{ index + 1 }}</td>
                        <td>{{ admin.name }}</td>
                        <td>{{ admin.email }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-admins-edit', params: { id: admin.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteAdmin(admin.id)" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>