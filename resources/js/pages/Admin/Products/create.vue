<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const categories = ref([]);
const router = useRouter();

const name = ref('');
const description = ref('');
const price = ref('');
const stock_quantity = ref('');
const category_id = ref('');
const image = ref(null);

const getCategory = () => {
    axios.get('/api/V1/categories', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    }).then((response) => {
        categories.value = response.data.data;
        console.log(response);
    }).catch((e) => {
        console.log(e);
    })
}

const formSubmit = () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('stock_quantity', stock_quantity.value);
    formData.append('category_id', category_id.value);
    formData.append('image', image.value.files[0]);
    console.log('form-data===>', formData);
    axios.post('/api/V1/products', formData, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`,
            "Content-Type": 'multipart/form-data'
        }
    }).then((response) => {
        if (response.status == 201) {
            router.push('/admin/products');
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: 'Created successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }).catch((e) => {
        console.log(response);
    })
}

onMounted(() => {
    getCategory();
})

</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Create Product</h3>
        </div>
        <div class="card container p-3">
            <form @submit.prevent="formSubmit">
                <div class="form-group">
                    <label>Product name</label>
                    <input type="text" v-model="name" class="form-control" placeholder="Product Name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" v-model="description" class="form-control" placeholder="Description">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" v-model="price" class="form-control" placeholder="Price">
                </div>
                <div class="form-group">
                    <label>Stock quantity</label>
                    <input type="text" v-model="stock_quantity" class="form-control" placeholder="Stock quantity">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" accept="image/*" ref="image" class="form-control">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select v-model="category_id" class="form-control">
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>