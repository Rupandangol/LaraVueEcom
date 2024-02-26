<script setup>
import axios from 'axios';
import AppNavbar from '../components/AppNavbar.vue';
import Footer from '../components/Footer.vue';
import { onMounted, ref } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    'id': {
        required: true,
        type: String
    }
});
const order = ref();
const getOrder = async () => {
    const response = await axios.get(`/api/V1/orders/${props.id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.status == 200) {
        order.value = response?.data?.data;
    } else {
        Swal.fire('Something Went Wrong');
    }
}
const getImageUrl = (img) => {
    return img ? `/storage/images/${img}` : '';
}
onMounted(() => {
    getOrder();
});
</script>
<template>
    <AppNavbar />
    <div class="container p-5 mt-2">
        <h3>Order Details</h3>
        <div class="card p-3">
            <div class="row ">
                <div class="col-md-4">
                    <h5>Details</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Order #</strong>: {{ order?.id }}</li>
                        <li class="list-group-item"><strong>Order Date :</strong> {{ order?.ordered_date }}</li>
                        <li class="list-group-item"><strong>Order Status :</strong>{{ order?.status }}</li>
                        <li class="list-group-item"><strong>SubTotal: </strong>{{ order?.total_price }}</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Shipping Address</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Order #</strong>:12</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                        <li class="list-group-item">A fourth item</li>
                        <li class="list-group-item">And a fifth one</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card p-3">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <td>Quantity</td>
                        <td>Image</td>
                        <td>Product Name</td>
                        <td>Serial #</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>SubTotal</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in order?.order_details">
                        <td>{{ item.quantity }}</td>
                        <td>Image</td>
                        <td>{{ item.product_id }}</td>
                        <td>{{ item.id }}</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.price * item.quantity }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card p-3">
            <h4>Amount Due</h4>
            <ul class="list-group">
                <li class="list-group-item"><strong>SubTotal : </strong>{{ order?.total_price }}</li>
                <li class="list-group-item">A second item</li>
                <li class="list-group-item">A third item</li>
                <li class="list-group-item">A fourth item</li>
                <li class="list-group-item">And a fifth one</li>
            </ul>
        </div>
    </div>
    <Footer />
</template>