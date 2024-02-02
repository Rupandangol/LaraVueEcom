<script setup>
import axios from 'axios';
import { reactive, ref, ssrContextKey } from 'vue';

const form = reactive({
    'email': "",
    'password': "",
})

const login = () => {
    axios.post('/api/V1/admins/login', { ...form })
        .then((response) => {
            const token = response.data.token;
            localStorage.setItem('admin-token', token);
            context.root.$router.push({ name: "admin-dashboard" });
            console.log(response.data.token);
        }).catch((error) => {
            console.log('ERROR==>', error);
        })
};
</script>
<template>
    <div class="d-flex justify-content-center mt-5">
        <div class="login-box">
            <div class="login-logo">
                <a href="/dashboard"><b>LE</b>com</a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in</p>
                    <form @submit.prevent="login">
                        <div class="input-group mb-3">
                            <input type="email" name="email" required v-model="form.email" class="form-control"
                                placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" required v-model="form.password" class="form-control"
                                placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>