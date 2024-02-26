<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const categories = ref([]);
const props = defineProps({
    id: {
        required: true,
        type: String
    }
});
const router = useRouter();

const name = ref('');
const description = ref('');
const price = ref('');
const stock_quantity = ref('');
const category_id = ref('');
const image = ref(null);
const showImage = ref(null);

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
const getData = () => {
    axios.get(`/api/V1/products/${props.id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    }).then((response) => {
        if (response.status == 200) {
            name.value = response.data.data.name;
            description.value = response.data.data.description;
            price.value = response.data.data.price;
            stock_quantity.value = response.data.data.stock_quantity;
            category_id.value = response.data.data.category.id;
            image.value = response.data.data.image;
            showImage.value = response.data.data.image;
        }
    }).catch((e) => {
        console.log(e);
    })
}

const formSubmit = () => {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('name', name.value);
    formData.append('description', description.value);
    formData.append('price', price.value);
    formData.append('stock_quantity', stock_quantity.value);
    formData.append('category_id', category_id.value);
    formData.append('image', image.value.files[0]);
    axios.post(`/api/V1/products/${props.id}`, formData, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`,
            "Content-Type": 'multipart/form-data'
        }
    }).then((response) => {
        if (response.status == 200) {
            router.push('/admin/products');
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Updated successfully",
                showConfirmButton: false,
                timer: 1500
            });
        }
    }).catch((e) => {
        console.log(response);
    })
}
const getImageUrl = (img) => {
    console.log('asdfasdf====>', img);

    return img ? `/storage/images/${img}` : '/images/test.jpg';
}
onMounted(() => {
    getCategory();
    getData();
})

</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Edit Product</h3>
        </div>
        <div class="card container p-3">
            <form @submit.prevent="formSubmit">
                <div class="form-group">
                    <label>Product name</label>
                    <input type="text" v-model="name" class="form-control" placeholder="Product Name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <!-- <input type="text" v-model="description" class="form-control" placeholder="Description"> -->
                    <textarea v-model="description" rows='5' class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" v-model="price" class="form-control" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stock quantity</label>
                            <input type="text" v-model="stock_quantity" class="form-control" placeholder="Stock quantity">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select v-model="category_id" class="form-control">
                        <option v-for="category in categories" :selected="(category_id == category.id) ? true : false"
                            :value="category.id">{{
                                category.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Image</label><br>
                    <img width="200px" height="200px" :src="getImageUrl(showImage)" alt=""><br><br>
                    <input type="file" accept="image/*" ref="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>