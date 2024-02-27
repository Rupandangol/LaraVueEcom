<script setup>
import Footer from '../components/Footer.vue';

import AppNavbar from '../components/AppNavbar.vue';
import { computed } from 'vue';
import store from '../store';

store.dispatch('getCartCount');
const test = computed(() =>  store.state.cartCount)

</script>
<template>
    <AppNavbar />
        <H1>About {{ test }}</H1>
    <Footer />
</template>