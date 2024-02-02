<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const admins = ref([]);
const getAdmins = () => {
    axios.get('/api/V1/admins')
        .then((response) => {
            admins.value = response.data.data;
        }).catch((error) => {
            console.log(error);
        })
}
onMounted(() => {
    getAdmins();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Admins</h3>
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
                    <tr v-for="(admin,index) in admins">
                        <td>{{index+1}}</td>
                        <td>{{admin.name}}</td>
                        <td>{{admin.email}}</td>
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