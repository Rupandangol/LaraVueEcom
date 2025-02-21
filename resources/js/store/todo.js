import axios from "axios";
import { defineStore } from "pinia";
import Swal from "sweetalert2";

export const useTodoStore = defineStore('todo', {
    state: () => ({
        todoList: [],
        loading: false
    }),
    actions: {
        async fetchTodoList() {
            const adminToken = localStorage.getItem('admin-token');
            const response = await axios.get('/api/V1/admins-todo-list', {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            });
            if (response.data.status == 'success') {
                this.todoList = response.data.data;
            }
        },
        async updateCompleted(todo_id) {
            try {
                this.loading = true;
                const adminToken = localStorage.getItem("admin-token");
                if (!adminToken) throw new Error("Admin token not found");

                const response = await axios.patch(
                    `/api/V1/admins-todo-list-update-status/${todo_id}`,
                    null, // no request body needed
                    {
                        headers: { Authorization: `Bearer ${adminToken}` }
                    }
                );
                this.loading = false;

                if (response.data.status === "success") {
                    return response.data.data;
                } else {
                    console.error("Failed to update todo:", response.data);
                }
            } catch (error) {
                this.loading = false;
                console.error("Error updating todo:", error);
            }
        },
        async addTask(formData) {
            const adminToken = localStorage.getItem("admin-token");
            const response = axios.post('/api/V1/admins-todo-list', { ...formData }, {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            });
            console.log(response);
        }
    }
})