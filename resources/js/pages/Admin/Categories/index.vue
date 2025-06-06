<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const param = reactive({
    'search': {
        'name': ''
    },
});
const categories = ref([]);
const getCategories = async () => {
    const response = await axios.get('/api/V1/categories', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        },
        params: {
            ...param.search
        }
    });
    if (response.status == 200) {
        categories.value = response.data.data;
    } else {
        Swal.fire('ERROR')
    }
}
const resetSearch = () => {
    param.search.name = '';
    getCategories();
}
const deleteCategories = (id) => {
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
            const adminToken = localStorage.getItem('admin-token');
            axios.delete(`/api/V1/categories/${id}`, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            }).then((response) => {
                if (response.data.status == 'success') {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                    getCategories();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Failed for some reason!",
                    });
                }
            }).catch((error) => {
                console.log(error);
            })

        }
    });
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
        <p class="container d-flex justify-content-end">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseCategorySearch"
                aria-expanded="false" aria-controls="collapseCategorySearch">
                <i class="fa fa-search"></i>
            </button>
        </p>

        <div class="container">
            <div class="collapse" id="collapseCategorySearch">
                <div>
                    <form @submit.prevent="getCategories" class="row">
                        <div class="form-group col-md-10">
                            <input type="text" v-model="param.search.name" class="form-control"
                                placeholder="Category Name">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button> &nbsp;
                            <button type="reset" class="btn btn-default" @click="resetSearch()"><i
                                    class="fa fa-sync"></i></button>
                        </div>
                    </form>
                </div>
            </div><br>
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