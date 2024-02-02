<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const orders = ref([]);

const getOrders = () => {
    axios.get(`/api/V1/orders`)
        .then((response) => {
            orders.value = response.data.data;
        }).catch((error) => {
            console.log(error);
        })
}
onMounted(() => {
    getOrders();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Orders</h3>
        </div>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Ordered By</th>
                        <th>Total Price</th>
                        <th>Shipping Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order,index) in orders">
                        <td>{{ index+1 }}</td>
                        <td>{{order.user.name}}</td>
                        <td>{{order.total_price}}</td>
                        <td>{{order.shipping_address}}</td>
                        <td>{{order.status}}</td>
                        <td>
                            <button class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></button>
                            <button class="btn btn-danger btn-sm mr-2"><i class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>