import { defineStore } from "pinia";
import api from "./api";
import Swal from "sweetalert2";

export const useDailyPlanner = defineStore('daily-planner', {
    state: () => ({
        recurringTask: []
    }),
    actions: {
        async destroyTask(id) {
            const response = await api.delete(`/admins-daily-schedule/${id}`);
            if (response.data.status = 'success') {
                Swal.fire({
                    title: "Deleted Successfully",
                })
            }
        },
        async fetchRecurringTask() {
            const response = await api.get(`/admins-recurring-daily-schedule`);
            if (response.data.status == 'success') {
                this.recurringTask = response.data.data;
            }
        },
        async addRecurringTaskToDailyPlanner(id,CDate) {
            const response = await api.post(`/admins-recurring-daily-schedule-add`, {
                'recurring_id': id,
                'date':CDate
            });
            console.log(response);
        }
    }
})