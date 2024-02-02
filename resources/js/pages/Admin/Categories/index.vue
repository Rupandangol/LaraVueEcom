<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const categories = ref([]);
const getCategories = async() => {
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
onMounted(() => {
    getCategories();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Categories</h3>
        </div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Name</th>
                        <th>Desciption</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, index) in categories">
                        <td>{{ index + 1 }}</td>
                        <td>{{ category.name }}</td>
                        <td>{{ category.description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>