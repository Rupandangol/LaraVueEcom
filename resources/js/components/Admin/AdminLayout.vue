<script setup>
import Header from './Header.vue';
import SideBar from './SideBar.vue';
import Footer from './Footer.vue';

</script>
<template>
    <Header />
    <SideBar />
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <slot></slot>
            </div>
        </div>
    </div>
    <Footer />
</template>