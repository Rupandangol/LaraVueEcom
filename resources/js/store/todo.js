import axios from "axios";
import { defineStore } from "pinia";

export const useTodoStore = defineStore('todo', {
    state: () => ({
        todoList: []
    }),
    actions: {
        async fetchTodoList() {
            const adminToken = localStorage.getItem('admin-token');
            const response = await axios.get('/api/V1/admins-todo-list', {
                headers: {
                    Authorization: `Bearer ${adminToken}`
                }
            });
            console.log(response);
        }
    }
})