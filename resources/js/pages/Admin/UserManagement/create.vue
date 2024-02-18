<script setup>
import { reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import Swal from 'sweetalert2';

const errors = ref('');
const msg = ref('');
const form = reactive({
    'name': "",
    'email': "",
    'password': ""
});
const router = useRouter();

const createUser = () => {
    errors.value = '';
    axios.post('/api/V1/users/register', { ...form }
    ).then((response) => {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Created Successfully",
            showConfirmButton: false,
            timer: 1500
        });
        router.push('/admin/users');
    }).catch((e) => {
        if (e.response.status === 422) {
            errors.value = e.response.data.errors;
        }
    })
}

</script>

<template>
    <AdminLayout>
        <ErrorMsg :msg="msg" :errors="errors" />
        <div class="heading m-2 p-3">
            <h3>Create User</h3>
        </div>
        <div class="card p-3 container">
            <form @submit.prevent="createUser">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="form.name" class="form-control" name="name" placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" v-model="form.email" class="form-control" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" v-model="form.password" class="form-control" name="password"
                        placeholder="Enter Password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </AdminLayout>
</template>