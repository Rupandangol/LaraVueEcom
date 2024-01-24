<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
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
        <img class="card-img-top"
            src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D"
            alt="..." />
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
                    :to="{ name: 'user-detail', params: { id: item?.id } }">View options</router-link></div>
        </div>
    </div>
</template>
