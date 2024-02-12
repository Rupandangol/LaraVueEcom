<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, ref } from 'vue';

const props = defineProps({
    'id': {
        required: true,
        type: String
    }
});

const order = ref([]);
const getOrder = () => {
    const adminToken = localStorage.getItem('admin-token');
    axios.get(`/api/V1/admin-orders/${props.id}`,
        {
            headers: {
                Authorization: `Bearer ${adminToken}`
            }
        }).then((response) => {
            order.value = response.data.data;
            console.log(response.data.data.order_details);
        }).catch((e) => {
            console.log(e.response);
        });
}

onMounted(() => {
    getOrder();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3">
            <h3>Order Edit</h3>
        </div>
        <div class="card m-2 p-3">
            <form>
                <div class="form-group">
                    <label>User Id</label>
                    <input type="text" class="form-control" placeholder="user id">
                </div>
                <div class="form-group">
                    <label>total_price</label>
                    <input type="text" class="form-control" placeholder="total_price">
                </div>
                <div class="form-group">
                    <label>shipping_address</label>
                    <input type="text" class="form-control" placeholder="shipping_address">
                </div>
                <div class="form-group">
                    <label>status</label>
                    <input type="text" class="form-control" placeholder="status">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Product_id</label>
                            <input type="text" class="form-control" placeholder="Product_id">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>quantity</label>
                            <input type="text" class="form-control" placeholder="quantity">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>price</label>
                            <input type="text" class="form-control" placeholder="price">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </AdminLayout>
</template>