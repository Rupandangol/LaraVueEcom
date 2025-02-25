import { defineStore } from "pinia";
import api from "./api";
import Swal from "sweetalert2";

export const useDailyPlanner = defineStore('daily-planner', {
    state: () => ({

    }),
    actions: {
        async destroyTask(id) {
            const response = await api.delete(`/admins-daily-schedule/${id}`);
            if(response.data.status='success'){
                Swal.fire({
                    title:"Deleted Successfully",
                })
            }
        }
    }
})