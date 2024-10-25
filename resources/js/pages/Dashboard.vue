<script setup>
import Header from '../components/Header.vue';
import Card from '../components/DashboardProductCard.vue';
import { onMounted, ref } from 'vue';
import Footer from '../components/Footer.vue';
import AppNavbar from '../components/AppNavbar.vue';
import Swal from 'sweetalert2';

const products = ref({ 'data': [] });
const userId = ref(null);
const getProducts = () => {
    axios.get(`/api/V1/products`).then((response) => {
        products.value = response.data.data;
    })
}
const getUserId = async () => {
    const token = localStorage.getItem('user-token');
    if (!token) {
        return ''; // Handle the case where the token is not present
    }
    try {
        const response = await axios.get('/api/V1/users-data', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        }).then((response) => {
            console.log('asdfasdfasdf', response.data.data.id);
            userId.value = response.data.data.id;
        });
    } catch (error) {
        console.log(error);
    }
}
onMounted(async () => {
    getProducts();
    await getUserId();
    window.Echo.private('order.status.' + userId.value)
        .listen('.order.status', (e) => {
            console.log('asdfasdfasdf==>', e);
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: '#' + e.order.id + ' Order Updated ' + e.order.status,
                showConfirmButton: false,
                timer: 2000
            });
        });

})
</script>

<template>
    <AppNavbar />
    <Header />
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