<script setup>

const props = defineProps({
    item: {
        required: true,
        type: Object
    }
})
const emit = defineEmits(['fetchData']);
const deleteItem = async () => {
    if (!window.confirm('You sure?')) {
        return;
    }
    try {
        const response = await axios.delete(`/api/V1/carts/${props.item.id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('user-token')}`
            }
        })
        if (response.data) { emit('fetchData') }
        console.log(response);
    } catch (error) {
        console.log(error);
    }
}
const getImageUrl = () => {
    return props?.item?.products?.[0].image ? `/storage/images/${props?.item?.products?.[0].image}` : '';
}
</script>



<template>
    <div class="card rounded-3 mb-4">
        <div class="card-body p-5">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2 col-lg-2 col-xl-2">
                    <img :src="getImageUrl()" class="img-fluid rounded-3" alt="Cotton T-shirt">
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3">
                    <p class="lead fw-normal mb-2">{{ item?.products?.[0]?.name }}</p>
                    <p><span class="text-muted">Unit Price: Rs.{{ item?.products?.[0]?.price }}</span></p>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-2">
                    <p class="lead fw-normal mb-2">Quantity</p>
                    <p><span class="text-muted">{{ item?.quantity }}</span></p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                    <h5 class="mb-0">Rs.{{ (item?.quantity) * (item?.products?.[0]?.price) }}</h5>
                </div>
                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                    <button @click="deleteItem()" class="btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
    </div>
</template>