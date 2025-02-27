import axios from "axios";
import { defineStore } from "pinia";
import Swal from "sweetalert2";
import api from "./api";

export const useTodoStore = defineStore('todo', {
    state: () => ({
        todoList: [],
        loading: false
    }),
    actions: {
        async fetchTodoList(archive = null) {
            const params = archive !== null ? { archive } : {}; // Dynamically add 'archive' if provided
            const response = await api.get('/admins-todo-list', { params });
            if (response.data.status == 'success') {
                this.todoList = response.data.data;
            }
        },
        async updateCompleted(todo_id) {
            try {
                this.loading = true;
                const response = await api.patch(`/admins-todo-list-update-status/${todo_id}`, null);
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
            const response = await api.post('/admins-todo-list', { ...formData });
            if (response.data.status == 'success') {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: `Task Added`,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
        },
        async deleteTask(id) {
            const response = await api.delete(`/admins-todo-list/${id}`);
            if (response.data.status == 'success') {
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: `Deleted Successfully`,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
        },
        async archiveTask(id) {
            this.loading = true;
            const response = await api.patch(`/admins-todo-list-archive/${id}`);
            if (response.data.status == 'success') {
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: `Archived`,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
            this.loading = false;
        }
    }
})