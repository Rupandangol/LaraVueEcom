<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';

const products = ref([]);
const getProducts = () => {
    axios.get('/api/V1/products')
        .then((response) => {
            products.value = response.data.data;
            console.log(response);
        }).catch((error) => {
            console.log(error);
        })
}
onMounted(() => {
    getProducts();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Products</h3>
        </div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Name</th>
                        <th>Desciption</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, index) in products">
                        <td>{{ index + 1 }}</td>
                        <td>{{ product?.name }}</td>
                        <td>{{ (product?.description).substring(0,20)+".." }}</td>
                        <td>{{ product?.price }}</td>
                        <td>{{ product?.stock_quantity }}</td>
                        <td>{{ product?.category.name }}</td>
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