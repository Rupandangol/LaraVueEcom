<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, reactive, ref } from 'vue';
import OrderStatusEnum from '../../../enums/OrderStatusEnum';
import Swal from 'sweetalert2';

const props = defineProps({
    'id': {
        required: true,
        type: String
    }
});
const formData=reactive({
    'user_id':'',
    'total_price':'',
    'status':'',
    'product_id':'',
    'quantity':'',
})

const order = ref([]);
const users = ref([]);
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
const getUsers=async()=>{
    const response= await axios.get('/api/V1/users');
    if(response.status==200){
        users.value=response.data.data;
    }else{
        Swal.fire('Error');
    }
}

onMounted(() => {
    getOrder();
    getUsers();
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
                    <select class="form-control">
                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>total_price</label>
                    <input type="text" class="form-control" placeholder="total_price">
                </div>
                <div class="form-group">
                    <label>status</label>
                    <select class="form-control">
                        <option v-for="item in OrderStatusEnum">{{item}}</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product_id</label>
                            <input type="text" class="form-control" placeholder="Product_id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>quantity</label>
                            <input type="text" class="form-control" placeholder="quantity">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </AdminLayout>
</template>