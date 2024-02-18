<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2';

const props = defineProps({
    id: {
        required: true,
        type: String
    }
})

const form = reactive({
    'name': "",
    'description': "",
})
const errors = ref('');
const router = useRouter();

const getData = () => {
    const adminToken = localStorage.getItem('admin-token');

    axios.get(`/api/V1/categories/${props.id}`, {
        headers:
            { Authorization: `Bearer ${adminToken}` }
    }).then((response) => {
        form.name = response.data.data.name;
        form.description = response.data.data.description;
        console.log(response);
    }).catch((error) => {
        console.log(error);
    })
}
const edit = () => {
    errors.value = '';
    const adminToken = localStorage.getItem('admin-token');

    axios.put(`/api/V1/categories/${props.id}`, { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        router.push('/admin/categories');
        Swal.fire('Updated Successfully');
        console.log(response);
    }).catch((e) => {
        if (e.response.status === 422) {
            for (const key in e.response.data.errors) {
                errors.value = e.response.data.errors
            }
        }
    })
}
onMounted(() => {
    getData();
})
</script>
<template>
    <AdminLayout>
        <div class="header m-2 p-3">
            <h3>Edit {{ $route.params }}</h3>
        </div>
        <div class="container card p-3">
            <div v-if="errors">
                <div v-for="error in errors" class="alert alert-danger">
                    {{ error }}
                </div>
            </div>
            <form @submit.prevent="edit">
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