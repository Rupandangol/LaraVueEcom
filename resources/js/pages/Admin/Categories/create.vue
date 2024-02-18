<script setup>
import { reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const form = reactive({
    'name': '',
    'description': ''
})
const errors = ref('');
const router = useRouter();
const create = async () => {
    errors.value = '';
    const adminToken = localStorage.getItem('admin-token');
    await axios.post('/api/V1/categories', { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Category Created",
            showConfirmButton: false,
            timer: 1500
        });
        router.push({ name: "admin-categories" });
        console.log(response);
    }).catch((e) => {
        if (e.response.status === 422) {
            for (const key in e.response.data.errors) {
                errors.value = e.response.data.errors
            }
        }
    });
}
</script>
<template>
    <AdminLayout>
        <div class="header m-2 p-3">
            <h3>Create</h3>
        </div>
        <div class="container card p-3">
            <div v-if="errors">
                <div v-for="error in errors" class="alert alert-danger">
                    {{ error }}
                </div>
            </div>
            <form @submit.prevent="create">
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" class="form-control" v-model="form.name" name="name" placeholder="Enter Category">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <textarea name="description" v-model="form.description" class="form-control" cols="30"
                        rows="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>