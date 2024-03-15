<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, reactive, ref } from 'vue';
import Swal from 'sweetalert2';

const param = reactive({
    'search': {
        'name': '',
        'price': '',
        'stock_quantity': '',
        'category_id': '',
    }
})
const products = ref([]);

const getProducts = async () => {
    const response = await axios.get('/api/V1/products',
        {
            params: {
                ...param.search
            }
        });
    if (response.status = 200) {
        products.value = response.data.data;
    } else {
        Swal.fire('ERROR');
    }
}

const deleteProduct = (id) => {
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
            axios.delete(`/api/V1/products/${id}`,
                {
                    headers: {
                        Authorization: `Bearer ${adminToken}`
                    }
                }).then((response) => {
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                        getProducts();
                    }
                }).catch((e) => {
                    console.log(e);
                });
        }
    });

}

const clearSearch = () => {
    param.search.name='';
    param.search.category_id='';
    param.search.price='';
    param.search.stock_quantity='';
    getProducts();
}

onMounted(() => {
    getProducts();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Products</h3>
            <router-link to="/admin/products/create" class="btn btn-success "><i class="fa fa-plus"></i>
                Create</router-link>
        </div>

        <p class="container d-flex justify-content-end">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseProductSearch"
                aria-expanded="false" aria-controls="collapseProductSearch">
                <i class="fa fa-search"></i>
            </button>
        </p>


        <div class="container">
            <div class="collapse" id="collapseProductSearch">
                <div>
                    <form @submit.prevent="getProducts()" class="row">
                        <div class="form-group col-md-8 ">
                            <input type="text" v-model="param.search.name" class="form-control"
                                placeholder="Product Name">
                        </div>

                        <div class="form-group col-md-4">
                            <select class="form-control" v-model="param.search.price">
                                <option>0-100</option>
                                <option>100-500</option>
                                <option>500-1000</option>
                                <option>>1000</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 ">
                            <input type="text" v-model="param.search.stock_quantity" class="form-control"
                                placeholder="Quantity">
                        </div>

                        <div class="form-group col-md-6">
                            <input type="text" v-model="param.search.category_id" class="form-control"
                                placeholder="Category">
                        </div>
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button> &nbsp;
                            <button type="reset" @click="clearSearch()" class="btn btn-primary"><i class="fa fa-sync"></i></button>
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
                        <td>{{ (product?.description).substring(0, 20) + ".." }}</td>
                        <td>{{ product?.price }}</td>
                        <td>{{ product?.stock_quantity }}</td>
                        <td>{{ product?.category.name }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-products-edit', params: { id: product.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteProduct(product.id)" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>