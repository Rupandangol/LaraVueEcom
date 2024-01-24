<script setup>
import AppNavbar from './components/AppNavbar.vue';
import Footer from './components/Footer.vue';
</script>

<template>
    <div id="app">
        <AppNavbar />
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <Footer />
    </div>
</template>