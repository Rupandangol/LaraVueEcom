<script setup>
import Header from '../components/Header.vue';
import Card from '../components/DashboardProductCard.vue';
import { onMounted, ref } from 'vue';
import Footer from '../components/Footer.vue';
import AppNavbar from '../components/AppNavbar.vue';
import Chat from './Chat.vue';

const products = ref({ 'data': [] });

const getProducts = () => {
    axios.get(`/api/V1/products`).then((response) => {
        products.value = response.data.data;
    })
}
onMounted(() => {
    getProducts();
})
</script>

<template>
    <AppNavbar />
    <Header />
    <Chat />
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <div v-for="(product, index) in products" class="col mb-5">
                    <Card :item="product" />
                </div>
            </div>
        </div>
    </section>
    <Footer />
</template>