<script setup>
import { onMounted, reactive, ref, watch, watchEffect } from 'vue';
import Card from '../components/DashboardProductCard.vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import AppNavbar from '../components/AppNavbar.vue';
import Footer from '../components/Footer.vue';
import Swal from 'sweetalert2';
import store from '../store';

const route = useRoute();
const rating = ref([]);
const avgRating = ref(null);
const product = ref([]);
const relatedProducts = ref([]);
const quantityValue = ref(1);
const formData = reactive({
    'review': '',
});
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
const addToCart = async () => {
    const headers = {
        Authorization: `Bearer ${localStorage.getItem('user-token')}`
    }
    const data = {
        product_id: product.value.id,
        quantity: quantityValue.value
    }
    await axios.post(`/api/V1/carts`, data, { headers })
        .then((response) => {
            store.dispatch('getCartCount');
            if (response.status == 201) {
                Swal.fire('Added to Cart');
            } else if (response.status == 200) {
                Swal.fire('Updated to Cart');
            }
        });
}

const getRating = async () => {
    const response = await axios.get(`/api/V1/ratings/${route.params.id}`);
    if (response.data.status == 'success') {
        rating.value = response.data.data.rating;
        avgRating.value = response.data.data?.avg?.[0]?.avgRating;
        console.log('asdfasdfasdf', response.data.data.avg?.[0]?.avgRating);
    }
}
console.log('AVG=====>', avgRating);
const updateRating = async (num) => {
    const response = await axios.post(`/api/V1/rating/${route.params.id}`, { 'rating': num }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.data.status == 'success') {
        getRating();
    }
}

const commentSubmit = async () => {
    const response = await axios.post(`/api/V1/ratings-comment/${route.params.id}`, formData, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.data.status == 'success') {
        Swal.fire(response.data.message);
        formData.review = '';
        getRating();
    } else {
        formData.review = '';

        Swal.fire(response.data.message);
    }
}

watchEffect(() => {
    getProductDetail();
    getRelatedProducts();
    getRating();
    window.scrollTo(0, 0);
})
onMounted(() => {
})
</script>
<template>
    <!-- Product section-->
    <AppNavbar />

    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" :src="`/storage/images/${product.image}`"
                        alt="..." /></div>
                <div class="col-md-6">
                    <div class="small mb-1">SKU: {{ product?.name }}-{{ product?.id }}</div>
                    <h1 class="display-5 fw-bolder">{{ product?.name }}</h1>
                    <div class="fs-5 mb-5">
                        <span>Rs.{{ product?.price }}</span>
                    </div>
                    <p class="lead">{{ product?.description }}</p>
                    <div v-if="product?.stock_quantity != 0" class="d-flex">
                        <input class="form-control text-center me-3" v-model="quantityValue" id="inputQuantity"
                            type="num" style="max-width: 3rem" />
                        <button @click="addToCart" class="btn btn-outline-dark flex-shrink-0" type="button">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </div>
                    <div class="badge badge-danger" v-else>
                        Out of Stock
                    </div>
                </div>
            </div><br>
            <div class="mt-2">
                <h5>Reviews and Rating</h5><br>
                <div style="background-color: #dbd9d9; text-align: center;">
                    <span v-for="item in 5">
                        <i class="fa fa-star fa-lg text-dark" v-if="avgRating >= item" @click="updateRating(item)"></i>
                        <i class="fa fa-star fa-lg text-light" v-else @click="updateRating(item)"></i>
                    </span>
                </div><br>
                <form @submit.prevent="commentSubmit">
                    <textarea v-model="formData.review" class="form-control" cols="10" rows="2"></textarea><br>
                    <button type="submit" class="btn btn-success">Comment</button>
                </form>
                <div class="mt-3">
                    <ul class="list-group">
                        <li v-for="item in rating" class="list-group-item">
                            <h4>{{ item.user.name }} <i v-for="icon in item.rating"
                                    class="fa fa-star fa-xs text-warning"></i></h4>
                            <p> {{ item.review }}</p>
                        </li>
                    </ul>
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
                    <Card :item="relatedProduct" />
                </div>
            </div>
        </div>
    </section>
    <Footer />
</template>
