<script setup>
import { computed, onMounted, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';

const orders = ref([]);

const getOrders = async () => {
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
const status = (data) => {
    if (data == 'delivered') {
        return 'badge badge-success';
    } else if (data == 'on_transit') {
        return 'badge badge-info';
    } else {
        return 'badge badge-warning';
    }
};
const deleteOrder = (id) => {
    if (!window.confirm('You sure?')) {
        return;
    }
    const adminToken = localStorage.getItem('admin-token');
    axios.delete(`/api/V1/admin-orders/${id}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            alert(response.data.message);
        }
        getOrders();

    }).catch((e) => {
        console.log(e);
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
                            <div :class="status(order?.status)">
                                {{ order?.status }}
                            </div>
                        </td>
                        <td>
                            <router-link title="Details" :to="{ name: 'admin-order-details', params: { id: order.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-file"></i></router-link>
                            <router-link :to="{ name: 'admin-order-edit', params: { id: order.id } }" title="Edit"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteOrder(order.id)" title="Delete" class="btn btn-danger btn-sm mr-2"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>