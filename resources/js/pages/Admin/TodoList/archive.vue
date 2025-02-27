<script setup>
import { storeToRefs } from 'pinia';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import { useTodoStore } from '../../../store/todo';
import { onMounted } from 'vue';

const todoStore = useTodoStore();

const { todoList } = storeToRefs(todoStore);
const { fetchTodoList, archiveTask,deleteTask } = todoStore;
const fetchTodoListArchive = async () => {
    await fetchTodoList(1);
}
const archive = (id) => {
    archiveTask(id);
    fetchTodoListArchive();
}
const removeTask = (id) => {
    deleteTask(id);
    fetchTodoListArchive();
}
onMounted(() => {
    fetchTodoListArchive();
})
</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Todo List Archive</h1>
                <div>
                    <a href="/admin/todo-list" class="btn btn-info">List</a>
                </div>
            </div>
            <div>
                <table class="table table-boarded">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                            <th>Due date</th>
                            <th>Completed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in todoList">
                            <td>{{ item.task }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.due_date }}</td>
                            <td>
                                <code :style="[item.is_completed?'color:green':'color:gray']"><i :class="[item.is_completed ? 'fa fa-check-square' : 'fa fa-square']"></i></code>
                            </td>
                            <td><button class="btn btn-danger" @click="removeTask(item.id)">Delete</button>&nbsp;
                                <button class="btn btn-info" :disabled="loading"
                                    @click="archive(item.id)">Restore</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>