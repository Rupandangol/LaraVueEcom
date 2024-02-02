<script setup>
import { onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const orders = ref([]);

const getOrders = async() => {
    const adminToken = localStorage.getItem('admin-token');
    await axios.get(`/api/V1/admin-orders`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
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
                        <th>Total Price (Rs.)</th>
                        <th>Shipping Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(order, index) in orders">
                        <td>{{ index + 1 }}</td>
                        <td>{{ order.user.name }}</td>
                        <td>{{ order.total_price }}</td>
                        <td>{{ order.shipping_address }}</td>
                        <td>
                            <div class="badge badge-warning" v-if="order.status==='pending'">
                                Pending
                            </div>
                            <div class="badge badge-info" v-else-if="order.status==='on_transit'">
                                On transit
                            </div>
                            <div class="badge badge-success" v-else>
                                Delivered
                            </div>
                        </td>
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