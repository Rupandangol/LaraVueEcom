<script setup>
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { Bar, Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'
import axios from 'axios';
import { computed, nextTick, onMounted, ref } from 'vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)
const dashboardData = ref([]);
const barLabel = ref([]);
const barData = ref([]);
const pieLabel = ref([]);
const pieData = ref([]);
const getDashboardData = async () => {
    const response = await axios.get('/api/V1/admin-dashboard', {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    });
    if (response.status == 200) {
        dashboardData.value = response.data;
        barLabel.value = response.data.orderDate.map(obj => obj.month);
        barData.value = response.data.orderDate.map(obj => obj.pending_count);
        pieLabel.value = response.data.orderStatusData.map(obj => obj.status);
        pieData.value = response.data.orderStatusData.map(obj => obj.total);
    }
}

const chartData = computed(() => {
    return {
        labels: Object.values(barLabel?.value),
        datasets: [{
            label: 'Orders',
            backgroundColor: ['#41B883'],
            data: Object.values(barData?.value)
        }]
    };
})

const chartOptions = {
    responsive: true
};
const data = computed(() => {
    return {
        labels: Object.values(pieLabel?.value),
        datasets: [
            {
                label: 'Status',
                backgroundColor: ['#E46651', '#00D8FF', '#DD1B16'],
                data: Object.values(pieData?.value)
            }
        ]
    }
});
const options = {
    responsive: true,
    maintainAspectRatio: false
};

onMounted(async () => {
    await nextTick();
    await getDashboardData();
})

</script>
<template>
    <AdminLayout>
        <div class="p-2">
            <h1> Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ dashboardData?.userCount }}</h3>
                        <p>Active Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <router-link to="/admin/users" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ dashboardData?.productCount }}</h3>
                        <p>Active Products</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <router-link to="/admin/products" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ dashboardData?.pendingOrderCount }}</h3>
                        <p>Pending Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <router-link to="/admin/orders" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ dashboardData?.deliveredOrderCount }}</h3>
                        <p>Total Delivered</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <router-link to="/admin/orders" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                </div>
            </div>

        </div>
        <div class="row col-md-12">
            <div class="card">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Orders
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div v-if="(barLabel.length > 0) && (barData.length > 0)" class="card bar">
                        <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div v-if="(pieLabel.length > 0) && (pieLabel.length > 0)" class="card pie">
                        <Pie :data="data" :options="options" />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.bar {
    padding: 10px;
    height: 300px;
}

.pie {
    height: 300px;
}
</style>