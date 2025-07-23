<script setup>
import { computed, onMounted, reactive, ref, watchEffect } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import axios from 'axios';
import { Bar, Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'
import { options } from '@fullcalendar/core/preact.js';
import { useTransactionStore } from '../../../store/transaction';
import { storeToRefs } from 'pinia';


// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const barLabel = ref([]);
const barData = ref([]);
const pieLabel = ref([]);
const pieData = ref([]);
const filters = ref([
    'description',
    'month',
    'date'
]);
const meilifilters = ref([
    'description',
]);

const transactionStore = useTransactionStore();
const { transactionData } = storeToRefs(transactionStore);
// const transactionData = ref([]);
const transactionDataPagination = ref([]);


const transactionImport = () => {
    transactionStore.transactionImport();
}

const customMeiliSearch = () => {
    const params = {};
    if (meilifilters.value.description) {
       params.description = meilifilters.value.description;
    }
    transactionStore.customMeiliSearch(params);
}
const fetchTransactionAnalytics = async (url) => {
    const response = await axios.get(url, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('admin-token')}`
        }
    });
    if (response.data.status == 'success') {
        if (response?.data?.data?.transactions) {
            transactionData.value = response.data.data;
            transactionDataPagination.value = {
                'next_page': response.data.data.transactions.next_page_url,
                'previous_page': response.data.data.transactions.prev_page_url,
            };
            barLabel.value = response?.data?.data.over_all_time_based_spendings.map(obj => obj.hour);
            barData.value = response?.data?.data.over_all_time_based_spendings.map(obj => obj.total);
            pieLabel.value = response?.data?.data.top_expenses.map(obj => obj.description);
            pieData.value = response?.data?.data.top_expenses.map(obj => obj.total_spent);
        }
    }
}


const handleSearch = () => {
    const params = new URLSearchParams();

    if (filters.value.description) {
        params.append('description', filters.value.description);
    }
    if (filters.value.month) {
        params.append('month', filters.value.month);
    }
    if (filters.value.date) {
        params.append('date', filters.value.date);
    }

    fetchTransactionAnalytics(`/api/V1/admin/transaction/analytics?${params.toString()}`)
}
const resetfilters = () => {
    filters.value.description = '';
    filters.value.month = '';
    filters.value.date = '';
    meilifilters.value.description = '';
    fetchTransactionAnalytics('/api/V1/admin/transaction/analytics')
}
// Chart data
const barChartData = computed(() => {
    return {
        labels: Object.values(barLabel?.value),
        datasets: [{
            label: 'Over all Hour based list',
            backgroundColor: ['#41B883'],
            data: Object.values(barData?.value)
        }],
    };
})

const chartOptions = {
    responsive: true
};

//pie chart
const pieChartData = computed(() => {
    return {
        labels: Object.values(pieLabel?.value),
        datasets: [
            {
                label: 'Top 5 expenses',
                backgroundColor: ['#E46651', '#00D8FF', '#DD1B16', '#535ea6', '#41ad8d'],
                data: Object.values(pieData?.value)
            }
        ]
    }
});
const pieOptions = {
    responsive: true,
    // maintainAspectRatio: false
};
onMounted(() => {
    fetchTransactionAnalytics('/api/V1/admin/transaction/analytics');
})
</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Transaction Analytics</h1>
                <button @click='transactionImport()' type="button" class="btn btn-success">Import</button>
            </div>
            <div class="card p-5">
                <h4>Total: <code>{{ transactionData.total }}</code></h4>
                <h4>Total Spent: <code>{{ transactionData.total_spent }}</code></h4>
                <form @submit.prevent="handleSearch" class="row">
                    <div class="form-group col-md-5">
                        <input type="text" v-model="filters.description" class="form-control" placeholder="Description">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="date" v-model="filters.date" class="form-control" placeholder="Date">
                    </div>
                    <div class="form-group col-md-5">
                        <input type="month" v-model="filters.month" class="form-control" placeholder="Month">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Search</button> &nbsp;
                        <button type="reset" @click="resetfilters()" class="btn btn-default"><i
                                class="fas fa-sync"></i></button>
                    </div>
                </form>

                <form @submit.prevent="customMeiliSearch" class="row">
                    <div class="form-group col-md-8">
                        <input type="text" v-model="meilifilters.description" class="form-control"
                            placeholder="Description">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Meili Search</button> &nbsp;
                        <button type="reset" @click="resetfilters()" class="btn btn-default"><i
                                class="fas fa-sync"></i></button>
                    </div>
                </form>
                <table class="table table-boarded">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Channel</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in transactionData.transactions?.data || []" :key="item.id">
                            <td>{{ item.id }}</td>
                            <td>{{ item.channel }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.date_time }}</td>
                            <td>{{ item.status }}</td>
                            <td>{{ item.debit }}</td>
                            <td>{{ item.credit }}</td>
                        </tr>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><button class="page-link" type="button"
                                @click="fetchTransactionAnalytics(transactionDataPagination.previous_page)">Previous</button>
                        </li>
                        <li class="page-item"><button class="page-link" type="button"
                                @click="fetchTransactionAnalytics(transactionDataPagination.next_page)">Next</button>
                        </li>
                    </ul>
                </nav>

                <h4>Forcast(Overall) for next month: <code>Rs. {{ transactionData.over_all_forecast }}</code></h4>
                <h4>Forcast(3 month) for next month: <code>Rs. {{ transactionData.three_month_forecast }}</code></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-2 mb-3">
                <div v-if="(barLabel.length > 0) && (barData.length > 0)" class="card p-5">
                    <h4 class="d-flex justify-content-center">Hour Based Spending Chart</h4>

                    <Bar :data="barChartData" :options="chartOptions" />
                </div>
            </div>
            <div class="col-md-4 offset-4">
                <div v-if="(pieLabel.length > 0) && (pieData.length > 0)" class="card p-5">
                    <h4 class="d-flex justify-content-center">Top 5 expenses</h4>

                    <Pie :data="pieChartData" :options="pieOptions" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>