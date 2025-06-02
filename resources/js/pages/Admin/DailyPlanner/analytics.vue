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

</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Analytics</h1>
                <h5>Total Data: <code>{{ analyicsData?.total_count }}</code></h5>
            </div>
            <div class="card p-3">
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
                            <td>{{ item.is_all_day }}</td>
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