<script setup>
import axios from 'axios';
import { onMounted, ref, watchEffect } from 'vue';
import CardProductCard from '../../components/CartProductCard.vue';
import AppNavbar from '../../components/AppNavbar.vue';
import Footer from '../../components/Footer.vue';
import Swal from 'sweetalert2';
import store from '../../store';

const cartData = ref({ 'data': [] });
const loading = ref(true);
const getData = async () => {
    loading.value = true;
    const token = localStorage.getItem('user-token');
    await axios.get('/api/V1/carts', {
        headers: {
            Authorization: `Bearer ${token}`
        }
    }).then((response) => {
        cartData.value = response.data.data;
        console.log(response.data.data);
    });
    loading.value = false;
    console.log(loading);
}
const order = () => {
    loading.value = true;
    var total = 0;
    var productId = [];
    var quantityNo = [];
    var priceNo = [];
    cartData.value.map((item) => {
        total += (item.quantity * item.products?.[0].price);
        productId.push(item.product_id);
        quantityNo.push(item.quantity);
        priceNo.push(item?.products?.[0].price);
    });
    const data = {
        'total_price': total,
        'product_id': productId,
        'quantity': quantityNo,
        'price': priceNo,
    }
    axios.post('/api/V1/orders', data, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            loading.value = false;
            store.dispatch('getCartCount');
            Swal.fire('Order Placed successfully');
            getData();
        } else if (response.data.status == 'failed') {
            Swal.fire('Order Failed, Out of stock product!! Pls remove the items that are out of stock')
        }
    }).catch((e) => {
        console.log(e);
    })
    console.log(data);
}
onMounted(() => {
    getData();
})
</script>
<template>
    <AppNavbar />
    <section class="h-100" style="background-color: #eee;">
        <div class="container h-100 py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-10">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                        <div>
                            <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!"
                                    class="text-body">price <i class="fas fa-angle-down mt-1"></i></a></p>
                        </div>
                    </div>
                    <div v-if="loading" class="d-flex justify-content-center">
                        <h1><i class=" fa fa-spinner fa-spin"></i></h1>
                    </div>
                    <div v-else>
                        <div v-for="cart in cartData">
                            <CardProductCard :item="cart" @fetchData="getData" />
                        </div>

                        <div v-if="cartData.length != 0" class="card">
                            <div class="card-body">
                                <button @click="order" type="button" class="btn btn-warning btn-block btn-lg">Proceed to
                                    Pay </button>
                            </div>
                        </div>
                        <div v-else>
                            <h3 class="d-flex justify-content-center"> <i class="fa fa-shopping-cart"></i>
                                &nbsp;&nbsp;&nbsp;Empty Cart</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <Footer />
</template>