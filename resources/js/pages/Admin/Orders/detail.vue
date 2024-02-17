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
const DELIVERED = 'delivered';
const PENDING = 'pending';
const IN_TRANSIT = 'in_transit';

const cStatus = ref('');

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
            cStatus.value = response.data.data.status;
            console.log(response.data.data);
        }).catch((e) => {
            console.log(e.response);
        });
}
const status = (data) => {
    if (data == DELIVERED) {
        return 'btn btn-success btn-sm';
    } else if (data == IN_TRANSIT) {
        return 'btn btn-info btn-sm';
    } else {
        return 'btn btn-warning btn-sm';
    }
}
const submitOrder = async () => {
    console.log(cStatus);
    const response = await axios.patch(`/api/V1/admin-status-orders/${props.id}`,
        { 'status': cStatus.value },
        {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('admin-token')}`
            }
        });
    if (response.data.status) {
        getOrder();
        document.getElementById('closeModal').click();
    };
}
onMounted(() => {
    getOrder();
})
</script>
<template>
    <AdminLayout>

        <div class="d-flex justify-content-end mb-2">



        </div>

        <div class="invoice p-3 mb-3">

            <div class="row">
                <div class="col-12">
                    <h4>
                        <h3>Details</h3>
                        <small class="float-right">Date: {{ order.ordered_date }}</small>
                    </h4>
                </div>

            </div>

            <div class="row ">
                <div class="col-sm-4 mb-4">
                    <h5>Order details</h5>
                    <strong>Order id: {{ order.id }}</strong><br>
                    <strong>Order date:</strong> {{ order.ordered_date }}<br>
                    <strong>Order Status:</strong> &nbsp;
                    <!-- Button trigger modal -->
                    <button type="button" :class="status(order.status)" data-toggle="modal" data-target="#orderStatusModal">
                        {{ order?.status }}
                    </button><br>
                    <strong>Sub Total:</strong> {{ order.total_price }}<br>
                </div>

                <div class="col-sm-4">
                    <h5>Customer Details</h5>
                    <address>
                        <strong> {{ order?.user?.name }}</strong><br>
                        {{ order?.user?.email }}<br>
                        9898989898<br>
                    </address>
                </div>

                <div class="col-sm-4">
                    <h5>Shipping Address</h5>
                    <address>
                        Test Address 1, Test Address 2 <br>
                        Test, 12345 <br>
                        CA, Nepal <br>
                    </address>
                </div>

            </div>


            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Quantity</th>
                                <th>Image</th>
                                <th>Product name</th>
                                <th>Serial Id</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in (order?.order_details)">
                                <td>{{ item.quantity }}</td>
                                <td><img :src="'/storage/images/'+item.product.image" width="35px" height="35px" alt=""></td>
                                <td>{{ item.product.name }}</td>
                                <td>{{ item.id }}</td>
                                <td>{{ item.product.description.substring(0, 40) + '...' }}</td>
                                <td>{{ item.price }}</td>
                                <td>{{ item.price * item.quantity }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">

                <!-- <div class="col-6">
                    <p class="lead">Payment Methods:</p>
                    <img src="" alt="Visa">
                    <img src="" alt="Mastercard">
                    <img src="" alt="American Express">
                    <img src="" alt="Paypal">
                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                        plugg
                        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                    </p>
                </div> -->

                <div class="col-6">
                    <p class="lead">Amount Due 2/22/2014</p>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width:50%">Subtotal:</th>
                                    <td>$250.30</td>
                                </tr>
                                <tr>
                                    <th>Tax (9.3%)</th>
                                    <td>$10.34</td>
                                </tr>
                                <tr>
                                    <th>Shipping:</th>
                                    <td>$5.80</td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>Rs. {{ order.total_price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            <div class="row no-print">
                <div class="col-12">
                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i
                            class="fas fa-print"></i> Print</a>
                    <!-- <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                        Payment
                    </button> -->
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                        <i class="fas fa-download"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="orderStatusModal" tabindex="-1" aria-labelledby="orderStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderStatusModalLabel">Update Order Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitOrder">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select v-model="cStatus" class="form-control form-control-lg">
                                    <option>{{ DELIVERED }}</option>
                                    <option>{{ PENDING }}</option>
                                    <option>{{ IN_TRANSIT }}</option>
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Choose the current status of the
                                    order</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="closeModal" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>