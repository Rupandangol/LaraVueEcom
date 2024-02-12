<script setup>
import axios from 'axios';
import { onMounted, ref, watchEffect } from 'vue';

const cartCount = ref(0);
const username = ref('');
const isLoggedIn = ref(false);
const logout = () => {
    if (confirm('Are you sure?')) {
        const headers = { Authorization: `Bearer ${localStorage.getItem('user-token')}` }
        axios.post('/api/V1/users/logout', {}, { headers })
            .then((response) => {
                if (response.status == 200) {
                    isLoggedIn.value = false;
                    alert('Logged Out Successfully');
                    localStorage.removeItem('user-token');
                }
            })
    }
}
const getNameFromToken = async () => {
    const token = localStorage.getItem('user-token');
    if (!token) {
        return ''; // Handle the case where the token is not present
    }
    try {
        const response = await axios.get('/api/V1/users-data', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        }).then((response) => {
            isLoggedIn.value = true;
            username.value = response.data.data.name;
        });
    } catch (error) {
        console.log(error);
    }
}
const getCartCount = () => {
    axios.get('/api/V1/carts-count',
        {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('user-token')}`
            }
        })
        .then((response) => {
            if (response.status == 200) {
                cartCount.value = response.data.count;
            }
        });
}
watchEffect(() => {
    isLoggedIn;
    username;
})
onMounted(() => {
    getNameFromToken();
    getCartCount();
})
</script>
<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">LEcom</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><router-link class="nav-link active" aria-current="page"
                            to="/dashboard">Home</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/about">About</router-link></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                            <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <div v-if="!isLoggedIn">
                        <router-link class="btn btn-default" to="/login">Login</router-link>
                    </div>
                    <div v-else>
                        <button @click="logout" type="button" class="btn btn-default" to="/logout">
                            Logout
                            (<code class="text text-success">{{ username }}</code>)
                        </button>
                    </div>
                    <router-link class="btn btn-outline-dark" to="/checkout">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ cartCount }}</span>
                    </router-link>
                </form>
            </div>
        </div>
    </nav>
</template>
