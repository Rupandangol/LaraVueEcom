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
                    <div v-else class="btn btn-primary">
                        aa {{ username }}
                    </div>
                    <router-link class="btn btn-outline-dark" to="/checkout">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </router-link>
                </form>
            </div>
        </div>
    </nav>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            isLoggedIn: localStorage.getItem('user-token') !== null,
            username: toString(this.getNameFromToken())
        };
    },
    methods: {
        async getNameFromToken() {
            const token = localStorage.getItem('user-token');
            // const decodedToken = JSON.parse(atob(token.split('.')[1]));
            if (!token) {
                return ''; // Handle the case where the token is not present
            }
            try {
                const response = await axios.get('/api/V1/users-data', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }).then((response) => {
                    return response.data.data.name;
                });
            } catch (error) {
                console.log(error);
            }

        }
    },
}
</script>