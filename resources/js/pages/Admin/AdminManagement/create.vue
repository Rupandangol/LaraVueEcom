<script setup>
import { reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const router = useRouter();
const errors = ref('');
const form = reactive({
    'name': '',
    'email': '',
    'password': '',
})

const createAdmin = () => {
    errors.value = '';
    const adminToken = localStorage.getItem('admin-token');
    axios.post('/api/V1/admins', { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.status == 201) {
            alert('Created Successfully');
            router.push('/admin/admins');
        }
    }).catch((e) => {
        if (e.response.status == 422) {
            errors.value = e.response.data.errors;
        }
    })
}

</script>
<template>
    <AdminLayout>
        <ErrorMsg :errors="errors" :msg="''" />
        <div class="heading m-2 p-3">
            <h3>Create Admin</h3>
        </div>
        <div class="container card p-3">
            <form @submit.prevent="createAdmin">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="form.name" class="form-control" name="name" placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" v-model="form.email" class="form-control" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" v-model="form.password" class="form-control" name="password"
                        placeholder="Enter Password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>