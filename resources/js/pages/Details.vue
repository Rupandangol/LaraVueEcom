<script setup>
import { onMounted, ref, watch, watchEffect } from 'vue';
import Card from '../components/DashboardProductCard.vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const product = ref();
const relatedProducts = ref();
const getProductDetail = () => {
    axios.get(`/api/V1/products/${route.params.id}`).then((response) => {
        product.value = response.data.data;
    })
}
const getRelatedProducts = () => {
    axios.get('/api/V1/related-products').then((response) => {
        relatedProducts.value = response.data.data;
    });
}
watchEffect(()=>{
    getProductDetail();
    getRelatedProducts();
    window.scrollTo(0,0);
})
onMounted(() => {
    // getProductDetail();
    // getRelatedProducts();
})
</script>
<template>
    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0"
                        src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D"
                        alt="..." /></div>
                <div class="col-md-6">
                    <div class="small mb-1">SKU: {{ product?.name }}-{{ product?.id }}</div>
                    <h1 class="display-5 fw-bolder">{{ product?.name }}</h1>
                    <div class="fs-5 mb-5">
                        <span>Rs.{{ product?.price }}</span>
                    </div>
                    <p class="lead">{{ product?.description }}</p>
                    <div class="d-flex">
                        <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1"
                            style="max-width: 3rem" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="button">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related products</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <div class="col mb-5" v-for="relatedProduct in relatedProducts">
                    <Card :item="relatedProduct"/>
                </div>
            </div>
        </div>
    </section></template>
