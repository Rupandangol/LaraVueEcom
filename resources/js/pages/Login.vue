<template>
    <div class="row">
        <div class="col-md-6 offset-3 p-5">
            <h1 class="d-flex justify-content-center">Login LEcom</h1>
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input name="email" v-model="email" type="email" class="form-control" id="email"
                        aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                        else.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" v-model="password" class="form-control" id="password"
                        placeholder="Password">
                </div>
                <!-- <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            'email': '',
            'password': '',
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/V1/users/login', {
                    email: this.email,
                    password: this.password,
                });
                const token = response?.data?.data?.token;
                localStorage.setItem('user-token', token);
                this.$router.push('/dashboard');

            } catch (error) {
                console.log('Login Failed', error);
            }
        }
    }
}
</script>