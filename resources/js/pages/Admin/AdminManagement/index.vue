<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import Swal from 'sweetalert2';

const param = reactive({
    search: {
        'name': '',
        'email': '',
    },
    sort: {
        'sort_field': '',
        'sort_direction': ''
    }
});
const loading = ref(true);
const msg = ref('');
const errors = ref('');
const admins = ref([]);
const getAdmins = async () => {
    loading.value=true;
    const adminToken = localStorage.getItem('admin-token');
    const response = await axios.get('/api/V1/admins', {
        params: {
            ...param.search
        },
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    });
    if (response.status == 200) {
        loading.value=false;
        admins.value = response.data.data;
    } else {
        Swal.fire('Error occured');
    }
}

const clearSearch = () => {
    param.search.name = '';
    param.search.email = '';
    getAdmins();
}

const deleteAdmin = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            msg.value = '';
            const adminToken = localStorage.getItem('admin-token');
            axios.delete(`/api/V1/admins/${id}`, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            }).then((response) => {
                if (response.data.status == 'success') {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                    msg.value = response.data.message;
                    getAdmins();
                }
            }).catch((e) => {
                console.log(e);
            })
        }
    });

}

onMounted(() => {
    getAdmins();
})
</script>
<template>
    <AdminLayout>
        <ErrorMsg :msg="msg" :errors="''" />
        <div class="heading m-2 p-3 d-flex justify-content-between">
            <h3>Admins</h3>
            <router-link to="/admin/admins/create" class="btn btn-success"><i class="fa fa-plus"></i> <i
                    class="fa fa-user-tie"></i></router-link>
        </div>
        <p class="container d-flex justify-content-end">
            <a class="btn btn-primary" data-toggle="collapse" href="#searchFormCollapse" role="button"
                aria-expanded="false" aria-controls="searchFormCollapse">
                <i class="fa fa-search"></i>
            </a>
        </p>

        <div class="container">
            <div class="collapse" id="searchFormCollapse">
                <div>
                    <form @submit.prevent="getAdmins()" class="row">
                        <div class="form-group col-md-5">
                            <input type="text" v-model="param.search.name" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" v-model="param.search.email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Search</button> &nbsp;
                            <button type="reset" @click="clearSearch()" class="btn btn-default"><i
                                    class="fas fa-sync"></i></button>
                        </div>
                    </form>
                </div>
            </div><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading" class="placeholder-glow">
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                        <td><span class="placeholder col-12"></span></td>
                    </tr>
                    <tr v-else v-for="(admin, index) in  admins ">
                        <td>{{ index + 1 }}</td>
                        <td>{{ admin.name }}</td>
                        <td>{{ admin.email }}</td>
                        <td>
                            <router-link :to="{ name: 'admin-admins-edit', params: { id: admin.id } }"
                                class="btn btn-warning btn-sm mr-2"><i class="fa fa-pen"></i></router-link>
                            <button @click="deleteAdmin(admin.id)" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>