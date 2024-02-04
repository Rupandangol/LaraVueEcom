<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const router = useRouter();
const props = defineProps({
    id: {
        required: true,
        type: String
    }
})

const errors = ref('');
const msg = ref('');
const form = reactive({
    'name': "",
    'email': "",
    'password': ""
});

const getUser = () => {
    axios.get(`/api/V1/users/${props.id}`).then((response) => {
        form.name = response.data.data.name;
        form.email = response.data.data.email;
    })
}

const editUser = () => {
    errors.value = '';
    const adminToken = localStorage.getItem('admin-token');
    axios.put(`/api/V1/users/${props.id}`, { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }
    ).then((response) => {
        console.log(response);
        alert('Edited Successfully');
        router.push('/admin/users');
    }).catch((e) => {
        if (e.response.status === 422) {
            errors.value = e.response.data.errors;
        }
    })
}
onMounted(() => {
    getUser();
})
</script>

<template>
    <AdminLayout>
        <ErrorMsg :msg="msg" :errors="errors" />
        <div class="heading m-2 p-3">
            <h3>Edit User <i class="fa fa-user"></i></h3>
        </div>
        <div class="card p-3 container">
            <form @submit.prevent="editUser">
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