<script setup>
import Header from '../components/Header.vue';
import Card from '../components/DashboardProductCard.vue';
</script>

<template>
    <Header/>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
                <div class="col mb-5">
                    <Card />
                </div>
            </div>
        </div>
    </section>
</template>