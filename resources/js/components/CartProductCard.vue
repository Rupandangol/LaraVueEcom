<script>
import axios from 'axios';

export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            quantity: this.item?.quantity
        }

    },
    methods: {
        async deleteItem() {
            try {
                const response = await axios.delete(`/api/V1/carts/${this.item.id}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('user-token')}`
                    }
                })
                if(response.data){}
                console.log(response);
            } catch (error) {
                console.log(error);
            }
        }
    }
}
</script>
<template>
    <div class="card rounded-3 mb-4">
        <div class="card-body p-5">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2 col-lg-2 col-xl-2">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cHJvZHVjdHxlbnwwfHwwfHx8MA%3D%3D"
                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3">
                    <p class="lead fw-normal mb-2">{{ item?.products?.[0]?.name }}</p>
                    <p><span class="text-muted">Unit Price: Rs.{{ item?.products?.[0]?.price }}</span></p>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                    <input id="form1" min="0" name="quantity" v-model="quantity" type="number"
                        class="form-control form-control-sm" />
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                    <h5 class="mb-0">Rs.{{ (item?.quantity) * (item?.products?.[0]?.price) }}</h5>
                </div>
                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                    <button @click="deleteItem" class="btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
    </div>
</template>