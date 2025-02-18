<script setup>
import { ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import axios from 'axios';


</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Todo List</h1>
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
                        <!-- <tr v-for="(item, index) in dailySchedule">
                            <td>{{ item.title }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.end_time }}</td>
                            <td>
                                <form @submit.prevent>
                                    <div class="form-group">
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
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>