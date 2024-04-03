<template>

    <div class="container d-flex p-3 flex-column">
        <main role="main" class="inner cover">
            <div v-if="status == 'Completed'">
                <h1 class="heading">Payment completed</h1>
                <p class="lead">Your invoice is sent in email.</p>
            </div>
            <div v-else-if="status == 'User canceled'">
                <h1 class="heading">User canceled</h1>
                <p class="lead">You have cancelled the payment</p>
            </div>

            <p class="lead">
                <a href="/dashboard" class="btn btn-lg btn-secondary">Go Back</a>
            </p>
        </main>

        <footer class="mastfoot mt-auto">
            <div class="inner">
                <p>Contact us <a href="http://localhost">LEcom</a></p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';


const route = useRoute()

const status = ref('');
const pidx = ref('');

const getStatus = () => {
    status.value = route.query.status;
    pidx.value = route.query.pidx;
}

const lookup = async () => {
    const response = await axios.post('/api/V1/lookup', {
        'pidx': pidx.value
    }
    , {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('user-token')}`
        }
    });
    console.log(response);
}

onMounted(() => {
    getStatus();
    lookup();
});

</script>