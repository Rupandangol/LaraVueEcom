<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { onMounted, reactive, ref } from 'vue';
import Swal from 'sweetalert2';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

const calendarRef = ref(null);
const tasks = ref([]);
const router = useRouter();
const handleDateClick = (arg) => {
    console.log(arg.dateStr);
    router.push(`/admin/daily-schedule/${arg.dateStr}`);
}
const calendarOptions = ref({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    dateClick: handleDateClick,
    events: tasks
});
const getTodayDate = () => {
    const calendarApi = calendarRef.value.getApi();
    const today = calendarApi.getDate();
    return today.toISOString().split('T')[0];
};
const fetchTasks = async () => {
    const date = getTodayDate();
    const adminToken = localStorage.getItem('admin-token');
    const response = await axios.get(`/api/V1/admins-daily-schedule-Monthly/${date}`, {
        headers: {
            Authorization: `Bearer ${adminToken}`
        }
    }).then((response) => {
        if (response.data.status == 'success') {
            tasks.value = response.data.data;
        }
    });
}


const errors = ref('');


onMounted(() => {
    fetchTasks();
});

</script>
<template>
    <AdminLayout>
        <ErrorMsg :errors="errors" :msg="''" />
        <div class="container">
            <div>
                <h1>Calender</h1>
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </div>
        </div>
    </AdminLayout>
</template>