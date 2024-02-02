<script setup>
import axios from 'axios';
import { onMounted, ref, watchEffect } from 'vue';
import CardProductCard from '../../components/CartProductCard.vue';
import AppNavbar from '../../components/AppNavbar.vue';
import Footer from '../../components/Footer.vue';

const cartData = ref({ 'data': [] });
const getData = async () => {
    const token = localStorage.getItem('user-token');
    try {
        await axios.get('/api/V1/carts', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        }).then((response) => {
            cartData.value = response.data.data;
            console.log(response.data.data);
        })
    } catch (error) {

    }
}
const handleRemoveItem = (item) => {
    // Handle the remove item event, you can remove the item from cartData
    const index = this.cartData.indexOf(item);
    if (index !== -1) {
        this.cartData.splice(index, 1);
    }
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
                            <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!" class="text-body">price <i
                                        class="fas fa-angle-down mt-1"></i></a></p>
                        </div>
                    </div>
                    <div v-for="cart in cartData">
                        <CardProductCard :item="cart" />
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn btn-warning btn-block btn-lg">Proceed to Pay</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <Footer />
</template>