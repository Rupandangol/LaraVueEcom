<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const loading = ref(true);
const param = reactive({
    'search': {
        'name': '',
        'status': '',
        'country': '',
        'zone': '',
        'district': '',
        'street': '',
        'zip_code': '',
    }
})
const orders = ref([]);

const getOrders = async () => {
    loading.value = true;
    const adminToken = localStorage.getItem('admin-token');
    await axios.get(`/api/V1/admin-orders`, {
        params: {
            ...param.search
        },
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        loading.value = false;
        orders.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    })
}

const resetSearch = () => {
    param.search.name = '';
    param.search.status = '';
    param.search.country = '';
    param.search.zone = '';
    param.search.district = '';
    param.search.street = '';
    param.search.zip_code = '';
    getOrders();
}
const status = (data) => {
    if (data == 'delivered') {
        return 'badge badge-success';
    } else if (data == 'in_transit') {
        return 'badge badge-info';
    } else {
        return 'badge badge-warning';
    }
};
const deleteOrder = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            const adminToken = localStorage.getItem('admin-token');
            axios.delete(`/api/V1/admin-orders/${id}`, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            }).then((response) => {
                if (response.data.status == 'success') {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                getOrders();

            }).catch((e) => {
                console.log(e);
            })
        }
    });
}
const reportGenerate = async () => {
    try {
        const response = await axios.get('/api/V1/admin-orders-report', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('admin-token')}`
            },
            responseType: 'blob'
        });
        const blob = new Blob([response.data], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'report.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.log(error);
    }
}
onMounted(() => {
    getOrders();
})
</script>
<template>
    <AdminLayout>
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Orders</h3>
            <button class="btn btn-success" target="_blank" @click="reportGenerate()">Generate Report</button>
            <router-link class="btn btn-info btn-sm" :to="{ name: 'admin-order-create' }">Create Order</router-link>
        </div>

        <p class="container d-flex justify-content-end">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseOrderSearch"
                aria-expanded="false" aria-controls="collapseOrderSearch">
                <i class="fa fa-search"></i>
            </button>
        </p>
        <div class="container">

            <div class="collapse" id="collapseOrderSearch">
                <div>
                    <form class="row" @submit.prevent="getOrders()">
                        <div class="form-group col-md-6">
                            <input v-model="param.search.name" type="text" class="form-control"
                                placeholder="Ordered by">
                        </div>
                        <div class="form-group col-md-6">
                            <!-- <input type="text" v-model="param.search.status" class="form-control" placeholder="Status"> -->
                            <select class="form-control" v-model="param.search.status">
                                <option value="">Choose status</option>
                                <option value="pending">Pending</option>
                                <option value="in_transit">In Transit</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-12 row">
                            <label>Shipping Address</label>
                            <div class="form-group col-md-3">
                                <input type="text" v-model="param.search.country" class="form-control"
                                    placeholder="Country">
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" v-model="param.search.zone" class="form-control" placeholder="Zone">
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" v-model="param.search.district" class="form-control"
                                    placeholder="District">
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" v-model="param.search.street" class="form-control"
                                    placeholder="Street">
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" v-model="param.search.zip_code" class="form-control"
                                    placeholder="Zip code">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                &nbsp;
                                <button type="reset" @click="resetSearch()" class="btn btn-default"><i
                                        class="fa fa-sync"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Ordered By</th>
                        <th>Order At</th>
                        <th>Total Price (Rs.)</th>
                        <th>Shipping Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading" v-for="placeholder in [1, 1, 1, 1]" class="placeholder-glow">
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                    </tr>
                    <tr v-else v-for="(order, index) in orders">
                        <td>{{ index + 1 }}</td>
                        <td>{{ order?.user?.name }}</td>
                        <td>{{ order?.ordered_date }}</td>
                        <td>{{ order.total_price }}</td>
                        <td>{{ order.country }},<br>{{ order.zone }},{{ order.district }},<br>{{ order.street }},{{
                            order.zip_code }}</td>
                        <td>
                            <div :class="status(order?.status)">
                                {{ order?.status }}
                            </div>
                        </td>

                        <td>
                            <router-link title="Details" :to="{ name: 'admin-order-details', params: { id: order.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-file"></i></router-link>
                            <router-link :to="{ name: 'admin-order-edit', params: { id: order.id } }" title="Edit"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteOrder(order.id)" title="Delete" class="btn btn-danger btn-sm mr-2"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>