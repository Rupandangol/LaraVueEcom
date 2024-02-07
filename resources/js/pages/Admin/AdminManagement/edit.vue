<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const router = useRouter();
const errors = ref('');

const props = defineProps({
    'id': {
        required: true,
        type: String
    }
});

const form = reactive({
    'name': '',
    'email': '',
    'password': '',
})
const getAdmin = () => {
    const adminToken = localStorage.getItem('admin-token');

    axios.get(`/api/V1/admins/${props.id}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        form.name = response.data.data.name;
        form.email = response.data.data.email;
    }).catch((e) => {
        console.log(e);
    })
}

const editAdmin = () => {
    errors.value = '';
    const adminToken = localStorage.getItem('admin-token');
    axios.put(`/api/V1/admins/${props.id}`, { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.status == 200) {
            alert('Updated Successfully');
            router.push('/admin/admins');
        }
    }).catch((e) => {
        if (e.response.status == 422) {
            errors.value = e.response.data.errors;
        }
    })
}

onMounted(() => {
    getAdmin();
})

</script>
<template>
    <AdminLayout>
        <ErrorMsg :errors="errors" :msg="''" />
        <div class="heading m-2 p-3">
            <h3>Edit Admin</h3>
        </div>
        <div class="container card p-3">
            <form @submit.prevent="editAdmin">
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