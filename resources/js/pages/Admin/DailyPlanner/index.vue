<script setup>
import axios from 'axios';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, reactive, ref } from 'vue';
import Swal from 'sweetalert2';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';

const props = defineProps({
    'date': {
        required: true,
        type: String
    }
});
const dailySchedule = ref([]);
const showModal = ref(false);
const form = reactive({
    'title': '',
    'description': '',
    'start_time': '',
    'end_time': '',
    'location': '',
    'date': props.date
})

const errors = ref('');

const fetchDailySchedule = async () => {
    const adminToken = localStorage.getItem('admin-token');
    const response = await axios.get(`/api/V1/admins-daily-schedule/${props.date}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    });
    if (response.data.status == 'success') {
        dailySchedule.value = response?.data?.data;
    }
}
const addTask = () => {
    const adminToken = localStorage.getItem('admin-token');
    axios.post('/api/V1/admins-daily-schedule', { ...form }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((res) => {
        if (res.status == 200) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: 'Task added Successfully',
                showConfirmButton: false,
                timer: 1500
            });
            fetchDailySchedule();
        }
    }).catch((e) => {
        console.log("errpr=========>", e.response.data.message);
        if (e.response.data.status == 'error') {
            errors.value = e.response?.data?.message;
        }
    });
    closeModal();
    clearForm();
}

const getDate = () => {
    if (props.date) {
        return props.date;
    } else {
        return new Date().toLocaleDateString();
    }
}
const clearForm = () => {
    form.title = '';
    form.description = '';
    form.start_time = '';
    form.end_time = '';
    form.location = '';
}

const updateStatus = async (id, status) => {
    const adminToken = localStorage.getItem('admin-token');

    const response = await axios.patch(`/api/V1/admins-daily-schedule-update-status/${id}`, { status }, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    });
    console.log(response);
}
onMounted(() => {
    getDate();
    fetchDailySchedule();
});

const closeModal = () => {
    showModal.value = false;

    // Ensure Bootstrap removes the backdrop
    const modalBackdrop = document.querySelector('.modal-backdrop');
    if (modalBackdrop) {
        modalBackdrop.remove();
    }

    // Remove Bootstrap's modal-open class from <body>
    document.body.classList.remove('modal-open');
};

</script>
<template>
    <AdminLayout>
        <ErrorMsg :errors="errors" :msg="''" />
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Daily Planner {{ props.date }}</h1>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" @click="showModal = true" data-toggle="modal"
                    data-target="#storeDailyScheduleModal">
                    <i class="fa fa-plus"> Add task</i>
                </button>
            </div>
            <div>
                <table class="table table-boarded">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Status</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dailySchedule">
                            <td>{{ item.title }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.end_time }}</td>
                            <td>
                                <form @submit.prevent>
                                    <div class="form-group">
                                        <!-- <label for="statusFormControlSelect1">{{ item.status }}</label> -->
                                        <select @change="updateStatus(item.id, $event.target.value)"
                                            class="form-control" id="statusFormControlSelect1">
                                            <option :selected="item.status == 'pending'" value="pending">Pending
                                            </option>
                                            <option :selected="item.status == 'completed'" value="completed">Completed
                                            </option>
                                            <option :selected="item.status == 'canceled'" value="canceled">Canceled
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            </td>
                            <td>{{ item.location }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="showModal" class="modal fade" id="storeDailyScheduleModal" tabindex="-1"
            aria-labelledby="storeDailyScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeDailyScheduleModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="showModal = false"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="row" @submit.prevent="addTask">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" v-model="form.title" name="title" class="form-control"
                                    placeholder="Task">
                            </div>
                            <div class="form-group">
                                <input type="text" name="description" v-model="form.description" class="form-control"
                                    placeholder="Description">
                            </div>
                            <div class="form-group">
                                <input type="time" name="start_time" v-model="form.start_time" class="form-control"
                                    placeholder="start_time">
                            </div>
                            <div class="form-group">
                                <input type="time" name="end_time" v-model="form.end_time" class="form-control"
                                    placeholder="end_time">
                            </div>
                            <div class="form-group">
                                <input type="text" name="location" v-model="form.location" class="form-control"
                                    placeholder="location">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showModal = false"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>