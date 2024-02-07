<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';

const props = defineProps({
    'id': {
        required: true,
        type: String
    }
});

const order = ref([]);
const getOrder = () => {
    const adminToken = localStorage.getItem('admin-token');
    axios.get(`/api/V1/admin-orders/${props.id}`,
        {
            headers: {
                Authorization: `Bearer ${adminToken}`
            }
        }).then((response) => {
            order.value = response.data.data;
            console.log(response.data.data.order_details);
        }).catch((e) => {
            console.log(e.response);
        });
}

onMounted(() => {
    getOrder();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Order Details</h3>
        </div>
        <table class="table table-bordered container">
            <thead>
                <tr>
                    <th>Sn.</th>
                    <th>Product name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in (order?.order_details)">
                    <td>{{ item.id }}</td>
                    <td>{{ item.product.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.price }}</td>
                </tr>
            </tbody>
        </table>
    </AdminLayout>
</template>