<script setup>
import axios from 'axios';
import AppNavbar from '../components/AppNavbar.vue';
import Footer from '../components/Footer.vue';
import Swal from 'sweetalert2';
import { onMounted, reactive, ref } from 'vue';

const shippingForm = reactive({
    'country': '',
    'zone': '',
    'district': '',
    'zip_code': '',
    'street': '',
});
const userId = ref('');
const profileForm = reactive({
    'name': '',
    'email': '',
    'old_password': '',
    'password': '',
});

const shippingAddressSubmit = async () => {
    const response = await axios.post('/api/V1/shipping-address', { ...shippingForm }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.status == 200 || response.status == 201) {
        Swal.fire('Updated Successfully');
    } else {
        Swal.fire('Something went wrong');
    }
}
const profileSubmit = async () => {
    const response = await axios.put(`/api/V1/users-data/${userId.value}`, { ...profileForm }, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.status == 200) {
        Swal.fire(response.data?.message);
    }
}

const getOldShippingAddress = async () => {
    const response = await axios.get('/api/V1/shipping-address/self', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.data.data?.country) {
        shippingForm.country = response.data.data.country;
        shippingForm.zone = response.data.data.zone;
        shippingForm.district = response.data.data.district;
        shippingForm.zip_code = response.data.data.zip_code;
        shippingForm.street = response.data.data.street;
    }
}
const getUserData = async () => {
    const response = await axios.get('/api/V1/users-data', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    if (response.status == 200) {
        profileForm.name = response.data.data.name;
        profileForm.email = response.data.data.email;
        userId.value = response.data.data.id;
    }
}
onMounted(() => {
    getOldShippingAddress();
    getUserData();
})
</script>
<template>
    <AppNavbar />
    <div class="container p-5">
        <h1 style="text-align: center;">Profile</h1>
        <hr>
        <br>
        <div class="row ">
            <div class="col-md-5 d-flex align-items-center">
                <form @submit.prevent="profileSubmit">
                    <h5>User Info</h5>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" v-model="profileForm.name" class="form-control" placeholder="Enter Full name">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input v-model="profileForm.email" type="email" class="form-control" placeholder="Enter email">
                        <small class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                    <div class="form-group">
                        <label>Old Password</label>
                        <input v-model="profileForm.old_password" type="password" class="form-control"
                            placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input v-model="profileForm.password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-md-7">
                <h5>Shipping Address</h5>
                <form @submit.prevent="shippingAddressSubmit">
                    <div class="form-group">
                        <label>Country</label>
                        <input v-model="shippingForm.country" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Zone</label>
                        <input v-model="shippingForm.zone" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <input v-model="shippingForm.district" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Street</label>
                        <input v-model="shippingForm.street" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Zip code</label>
                        <input v-model="shippingForm.zip_code" type="text" pattern="[0-9]*" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <br><br>
    <Footer />
</template>