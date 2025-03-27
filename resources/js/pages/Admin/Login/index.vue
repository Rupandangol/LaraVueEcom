<template>
    <div class="d-flex justify-content-center mt-5">
        <div class="login-box">
            <div class="login-logo">
                <a href="/dashboard"><b>LE</b>com</a>
            </div>
            <div v-if="errorMsg" class="alert alert-danger">
                {{ errorMsg }}
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in</p>
                    <form @submit.prevent="login">
                        <div class="input-group mb-3">
                            <input type="email" name="email" required v-model="email" class="form-control"
                                placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" required v-model="password" class="form-control"
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
<script>
import { setAuthToken } from "../../../store/api";
export default {
    
    data() {
        return {
            'email': '',
            'password': '',
            'errorMsg': '',
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/V1/admins/login', {
                    email: this.email,
                    password: this.password,
                });
                const token = response?.data?.token;
                // localStorage.setItem('admin-token', token);
                setAuthToken(token);
                this.$router.push('/admin/dashboard');

            } catch (error) {
                this.errorMsg = error.response.data.message;
                console.log('Login Failed', error.response.data.message);
            }
        }
    }
}
</script>