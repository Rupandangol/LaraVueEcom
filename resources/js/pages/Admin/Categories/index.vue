<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const categories = ref([]);
const getCategories = async () => {
    const adminToken = localStorage.getItem('admin-token');
    console.log(adminToken);
    await axios.get('/api/V1/categories', {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        categories.value = response.data.data;
        console.log(response);
    }).catch((error) => {
        console.log(error);
    })
}

const deleteCategories = (id) => {
    if (!window.confirm('you sure?')) {
        return;
    }
    const adminToken = localStorage.getItem('admin-token');
    axios.delete(`/api/V1/categories/${id}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            alert('Successfully Deleted');
            getCategories();
        } else {
            alert('Failed for some reason');
        }
    }).catch((error) => {
        console.log(error);
    })
}

onMounted(() => {
    getCategories();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Categories</h3>
            <router-link to="/admin/categories/create" class="btn btn-success"><i class="fa fa-plus"></i>
                Create</router-link>
        </div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Name</th>
                        <th>Desciption</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, index) in categories">
                        <td>{{ index + 1 }}</td>
                        <td>{{ category.name }}</td>
                        <td>{{ (category.description).substring(0, 20) + ".." }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-categories-edit', params: { id: category.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i>
                            </router-link>
                            <button @click="deleteCategories(category.id)" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>