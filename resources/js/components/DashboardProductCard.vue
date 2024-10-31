<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    methods: {
        getImageUrl() {
            return this.item.image ? `/storage/images/${this.item.image}` : '/images/test.jpg';
        }
    }
}
</script>
<template>
    <div v-if="item?.id" class="card h-100">
        <!-- Sale badge-->
        <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale
        </div>
        <!-- Product image-->
        <img class="card-img-top" :src="getImageUrl()" alt="..." />
        <!-- Product details-->
        <div class="card-body p-4">
            <div class="text-center">
                <!-- Product name-->
                <h5 class="fw-bolder">{{ item?.name }}</h5>
                <!-- Product price-->
                Rs {{ item.price }}<br>
                <span>category: {{ item?.category?.name }}</span>
            </div>
        </div>
        <!-- Product actions-->
        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center"><router-link class="btn btn-outline-dark mt-auto"
                    :to="{ name: 'product-detail', params: { id: item?.id } }">View options</router-link></div>
        </div>
    </div>
</template>
