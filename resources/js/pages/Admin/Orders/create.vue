<script setup>
import { reactive } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';

const formData = reactive([{
    'product_id': '',
    'quantity': 0,
}]);


const addProduct = () => {
    formData.push({
        'product_id': '',
        'quantity': 0
    });
}


</script>

<template>
    <AdminLayout>
        <div class="heading p-3 m-2">
            <h3>Create</h3>
        </div>
        <div class="card p-3 ">
            <form>
                <!-- <div class="form-group">
                    <label>User Id</label>
                    <select class="form-control">
                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>total_price</label>
                    <input type="text" class="form-control" placeholder="total_price">
                </div>
                <div class="form-group">
                    <label>status</label>
                    <select class="form-control">
                        <option v-for="item in OrderStatusEnum">{{ item }}</option>
                    </select>
                </div> -->

                <div>
                    <p>{{ formData }}</p>
                </div>
                <div v-for="(item, index) in formData">
                    <div class="form-group">
                        <label>Product_id</label>
                        <input v-model="item.product_id" type="text" class="form-control" placeholder="Product_id">
                    </div>
                    <div class="form-group">
                        <label>quantity</label>
                        <input v-model="item.quantity" type="text" class="form-control" placeholder="quantity">
                    </div>
                    <button type="button" @click="addProduct()" class="btn btn-success">Add
                        Product</button>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>