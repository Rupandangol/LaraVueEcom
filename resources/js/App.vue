<script setup>
import AppNavbar from './components/AppNavbar.vue';
</script>

<template>
    <div id="app">
        <AppNavbar />
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
    </div>
</template>