<script setup>
import Footer from '../components/Footer.vue';

import AppNavbar from '../components/AppNavbar.vue';
import { computed } from 'vue';
import store from '../store';
import api from '../store/api';
import axios from 'axios';

store.dispatch('getCartCount');
const test = computed(() => store.state.cartCount)
const stripeTest = async () => {
    const token = localStorage.getItem('user-token');
    try {
        const response = await axios.post('/api/V1/create-subscription', {}, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        if (response.data.url) {
            window.location.href = response.data.url;
        } else {
            console.error('Stripe checkout URL not received.');
        }
    } catch (error) {
        console.error('Stripe checkout error:', error);
    }
};
</script>
<template>
    <AppNavbar />
    <H1>About {{ test }}</H1>
    <button @click="stripeTest()">TEST PAY</button>
    <Footer />
</template>