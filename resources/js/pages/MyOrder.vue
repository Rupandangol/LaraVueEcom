<script setup>
import axios from 'axios';
import AppNavbar from '../components/AppNavbar.vue';
import Footer from '../components/Footer.vue';
import { onMounted, ref } from 'vue';

const orders = ref();
const getOrders = async () => {
    const response = await axios.get('/api/V1/orders', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    orders.value = response?.data?.data;
    console.log(response);
}
const status = (data) => {
    if (data == 'delivered') {
        return 'badge badge-success';
    } else if (data == 'in_transit') {
        return 'badge badge-info';
    } else {
        return 'badge badge-warning';
    }
};
onMounted(() => {
    getOrders();
})
</script>
<template>
    <AppNavbar />
    <div class="container p-5 mt-2">
        <h3>My Order</h3>
        <div class="card p-2">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Sub Total</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in orders">
                        <td><router-link :to="{ name: 'user-my-order-detail', params: { id: order.id } }">{{ order.id
                        }}</router-link></td>
                        <td>{{ order.ordered_date }}</td>
                        <td><span :class="status(order.status)">{{ order.status }}</span></td>
                        <td>Rs. {{ order.total_price }}</td>
                        <td>{{ order.order_details.length }}</td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<Footer /></template>