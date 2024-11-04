<script setup>
import Swal from 'sweetalert2';
import store from '../store';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const props = defineProps({
    item: {
        required: true,
        type: Object
    }
})
const selectedItem = ref(null);
const emit = defineEmits(['fetchData']);
const deleteItem = () => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await axios.delete(`/api/V1/carts/${props.item.id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('user-token')}`
                    }
                })
                if (response.data) { emit('fetchData') }
                store.dispatch('getCartCount');
                Swal.fire({
                    title: "Removed!",
                    text: "Removed from the cart",
                    icon: "success"
                });
            } catch (error) {
                console.log(error);
            }
        }
    });

}
const getImageUrl = () => {
    return props?.item?.products?.[0]?.image ? `/storage/images/${props?.item?.products?.[0].image}` : '';
}
const stockDataClass = () => {
    return props?.item?.products?.[0]?.stock_quantity == 0 ? 'badge badge-danger' : 'badge badge-info';
}

const toggleSelectProduct = async () => {
    const newSelectedStatus = selectedItem.value === 1 ? 0 : 1;
    await axios.patch(`/api/V1/carts/${props.item.id}`, {
        'selected': props.item.selected === 1 ? 0 : 1
    }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    }).then((res) => {
        if (res.data.status == 'success') {
            selectedItem.value = newSelectedStatus;
        }
    }).catch((e) => {
        console.log(e);
    })
}
onMounted(() => {
    selectedItem.value = props?.item?.selected;
})

</script>



<template>
    <div class="card rounded-3 mb-4">
        <div class="card-body p-5">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2 col-lg-2 col-xl-2">
                    <button @click="toggleSelectProduct()" class="btn btn-outline-success"><i
                            :class="[selectedItem === 1 ? 'fa fa-check-square' : 'fa fa-square']"></i></button>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2">
                    <img :src="getImageUrl()" class="img-fluid rounded-3" alt="Cotton T-shirt">
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3">
                    <p class="lead fw-normal mb-2">{{ item?.products?.[0]?.name }}</p>
                    <p>
                        <span class="text-muted">Unit Price: Rs.{{ item?.products?.[0]?.price }}</span><br>
                        <span class="text-muted">Stock Quantity:</span> <span :class="stockDataClass()">
                            {{ item?.products?.[0]?.stock_quantity == 0 ? 'Out of Stock' :
                                (item?.products?.[0]?.stock_quantity) }}</span>
                    </p>
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