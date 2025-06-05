<script setup>
import { storeToRefs } from 'pinia';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { useTodoStore } from '../../../store/todo';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { Bar, Doughnut, Line, Pie, PolarArea } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement, PointElement, Filler } from 'chart.js'


ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement, PointElement, Filler)

const analyicsData = ref([]);
const barTitleGroupLabel = ref([]);
const barTitleGroupData = ref([]);
const barDateCountLabel = ref([]);
const barDateCountData = ref([]);
const lineTimeCountLabel = ref([]);
const lineTimeCountData = ref([]);
const lineLocationCountLabel = ref([]);
const lineLocationCountData = ref([]);
const donutIsAllDayLabel = ref([]);
const donutIsAllDayData = ref([]);
const pieStatusCountLabel = ref([]);
const pieStatusCountData = ref([]);

const filters = ref([
    'date',
    'description',
    'start_time',
    'end_time',
    'is_all_day',
    'location',
    'status',
    'title'
]);

const fetchDailyScheduleAnalytics = async (url) => {
    const response = await axios.get(url, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    });
    if (response.status == 200) {
        analyicsData.value = response.data.data;
        barTitleGroupLabel.value = response.data.data?.title_group?.map(obj => obj.title);
        barTitleGroupData.value = response.data.data?.title_group?.map(obj => obj.total);
        barDateCountLabel.value = response.data.data?.date_count?.map(obj => obj.date);
        barDateCountData.value = response.data.data?.date_count?.map(obj => obj.total);
        lineTimeCountLabel.value = response.data.data?.time_count?.map(obj => obj.start_time);
        lineTimeCountData.value = response.data.data?.time_count?.map(obj => obj.total);
        lineLocationCountLabel.value = response.data.data?.location_count?.map(obj => obj.location);
        lineLocationCountData.value = response.data.data?.location_count?.map(obj => obj.total);
        donutIsAllDayLabel.value = response.data.data?.is_all_day_count?.map(obj => obj.is_all_day);
        donutIsAllDayData.value = response.data.data?.is_all_day_count?.map(obj => obj.total);
        pieStatusCountLabel.value = response.data.data?.status_count?.map(obj => obj.status);
        pieStatusCountData.value = response.data.data?.status_count?.map(obj => obj.total);
    }
}
onMounted(() => {
    fetchDailyScheduleAnalytics('/api/V1/daily-schedule-analytics');
})

// Bar Chart data
const barTitleGroupChartData = computed(() => {
    return {
        labels: Object.values(barTitleGroupLabel?.value),
        datasets: [{
            label: 'Top daily tasks',
            backgroundColor: ['#41B883'],
            data: Object.values(barTitleGroupData?.value)
        }],
    };
})
const chartOptions = {
    responsive: true
};
// Bar Chart data
const barDateCountChartData = computed(() => {
    return {
        labels: Object.values(barDateCountLabel?.value),
        datasets: [{
            label: 'Top date-wise tasks',
            backgroundColor: ['#E46651'],
            data: Object.values(barDateCountData?.value)
        }],
    };
})
// Line Chart data
const lineTimeCountChartData = computed(() => {
    return {
        labels: Object.values(lineTimeCountLabel?.value),
        datasets: [
            {
                label: 'Time With Most task',
                backgroundColor: ['#41B883'],
                data: Object.values(lineTimeCountData?.value)
            }
        ],
    };
})

// Area Chart data
const AreaLocationCountChartData = computed(() => {
    return {
        labels: Object.values(lineLocationCountLabel?.value),
        datasets: [
            {
                label: 'Location Count',
                fill: true,
                backgroundColor: ['#535ea6'],
                data: Object.values(lineLocationCountData?.value)
            }
        ],
    };
})

// Donut Chart data
const donutIsAllDayChartData = computed(() => {
    return {
        labels: Object.values(donutIsAllDayLabel?.value),
        datasets: [{
            label: 'Full day task',
            backgroundColor: ['#E46651', '#00D8FF',],
            data: Object.values(donutIsAllDayData?.value)
        }],
    };
})

// Pie Chart data
const pieStatusCountChartData = computed(() => {
    return {
        labels: Object.values(pieStatusCountLabel?.value),
        datasets: [{
            label: 'Full day task',
            backgroundColor: ['#E46651', '#00D8FF', '#DD1B16', '#535ea6', '#41ad8d'],
            data: Object.values(pieStatusCountData?.value)
        }],
    };
})

const handleSearch = () => {
    const params = new URLSearchParams();
    if (filters.value.date) {
        params.append('date', filters.value.date);
    }
    if (filters.value.description) {
        params.append('description', filters.value.description);
    }
    if (filters.value.start_time) {
        params.append('start_time', filters.value.start_time);
    }
    if (filters.value.end_time) {
        params.append('end_time', filters.value.end_time);
    }
    if (filters.value.is_all_day) {
        params.append('is_all_day', filters.value.is_all_day);
    }
    if (filters.value.location) {
        params.append('location', filters.value.location);
    }
    if (filters.value.status) {
        params.append('status', filters.value.status);
    }
    if (filters.value.title) {
        params.append('title', filters.value.title);
    }
    fetchDailyScheduleAnalytics(`/api/V1/daily-schedule-analytics?${params.toString()}`)
}
const resetfilters = () => {
    filters.value.date = '';
    filters.value.description = '';
    filters.value.start_time = '';
    filters.value.end_time = '';
    filters.value.is_all_day = '';
    filters.value.location = '';
    filters.value.status = '';
    filters.value.title = '';
    fetchDailyScheduleAnalytics('/api/V1/daily-schedule-analytics');
}
</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Analytics</h1>
                <h5>Total Data: <code>{{ analyicsData?.total_count }}</code></h5>
            </div>
            <div class="card p-3">
                <form @submit.prevent="handleSearch" class="row">
                    <div class="form-group col-md-3">
                        <label for="date">Date</label>
                        <input type="date" v-model="filters.date" class="form-control" placeholder="Date">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="description">Description</label>
                        <input type="text" v-model="filters.description" class="form-control" placeholder="Description">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="start_time">Start Time</label>
                        <input type="time" v-model="filters.start_time" class="form-control" placeholder="Start time">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_time">End Time</label>
                        <input type="time" v-model="filters.end_time" class="form-control" placeholder="Start time">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_all_day">Is all Day</label>
                        <select class="form-control" v-model="filters.is_all_day">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="location">Location</label>
                        <input type="text" v-model="filters.location" class="form-control" placeholder="Start time">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="status">Status</label>
                        <select v-model="filters.status" class="form-control">
                            <option value="in_progress">In progress</option>
                            <option value="pending">Pending</option>
                            <option value="canceled">Canceled</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="title">Title</label>
                        <input type="text" v-model="filters.title" class="form-control" placeholder="Start time">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Search</button> &nbsp;
                        <button type="reset" @click="resetfilters()" class="btn btn-default"><i
                                class="fas fa-sync"></i></button>
                    </div>
                </form>
                <table class="table table-boarded">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>date</th>
                            <th>description</th>
                            <th>start time</th>
                            <th>end time</th>
                            <th>is_all_day</th>
                            <th>location</th>
                            <th>status</th>
                            <th>title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in analyicsData.dailySchedule?.data">
                            <td>{{ item.id }}</td>
                            <td>{{ item.date }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.end_time }}</td>
                            <td>
                                <span class="badge badge-warning" v-if="item.is_all_day">Yes</span>
                                <span class="badge badge-success" v-else>No</span>
                            </td>
                            <td>{{ item.location }}</td>
                            <td>{{ item.status }}</td>
                            <td>{{ item.title }}</td>
                        </tr>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><button
                                :disabled="analyicsData?.dailySchedule?.prev_page_url === null ? true : false"
                                class="page-link" type="button"
                                @click="fetchDailyScheduleAnalytics(analyicsData?.dailySchedule?.prev_page_url)">Previous</button>
                        </li>
                        <li class="page-item"><button class="page-link" type="button"
                                :disabled="analyicsData?.dailySchedule?.next_page_url === null ? true : false"
                                @click="fetchDailyScheduleAnalytics(analyicsData?.dailySchedule?.next_page_url)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-10 offset-1">
                    <div class="card p-5 ">
                        <Bar :data="barTitleGroupChartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="col-md-5 offset-1">
                    <div class="card p-5 ">
                        <Line :data="lineTimeCountChartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card p-5 ">
                        <Line :data="AreaLocationCountChartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="col-md-5 offset-1">

                    <div class="card p-5 ">
                        <Doughnut :data="donutIsAllDayChartData" :options="chartOptions" />
                    </div>
                </div>
                <div class="col-md-5">

                    <div class="card p-5 ">
                        <Pie :data="pieStatusCountChartData" :options="chartOptions" />
                    </div>
                </div>

                <div class="col-md-10 offset-1">
                    <div class="card p-5 ">
                        <Bar :data="barDateCountChartData" :options="chartOptions" />
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>