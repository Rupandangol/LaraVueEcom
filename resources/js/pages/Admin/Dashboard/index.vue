<script setup>
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { Bar, Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'
import axios from 'axios';
import { onMounted, ref } from 'vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)
const dashboardData = ref([]);
const barLabel = ref([]);
const barData = ref([]);
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
        console.log(barLabel.value,barData);
    }
}

const chartData = {
    // labels: ['January', 'February', 'March'],
    // datasets: [{ data: [40, 20, 12] }]
    labels: barLabel?.value,
    datasets: [{ data: barData?.value }]
};
const chartOptions = {
    responsive: true
};
const data = {
    labels: ['VueJs', 'EmberJs', 'ReactJs', 'AngularJs'],
    datasets: [
        {
            backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
            data: [40, 20, 80, 10]
        }
    ]
};
const options = {
    responsive: true,
    maintainAspectRatio: false
};

onMounted(() => {
    getDashboardData();
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
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div  class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                            <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
                        </div>
                        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                            <Pie :data="data" :options="options" />
                        </div>
                    </div>
                </div>
            </div>
    </div>
</AdminLayout></template>