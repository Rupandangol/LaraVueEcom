<script setup>
import { onMounted, reactive, ref } from 'vue';
import AdminLayout from '../../../components/Admin/AdminLayout.vue';
import ErrorMsg from '../../../components/layout/ErrorMsg.vue';
import axios from 'axios';
import { useTodoStore } from '../../../store/todo';
import { storeToRefs } from 'pinia';

const todoStore = useTodoStore();
const { todoList, loading } = storeToRefs(todoStore);
const {addTask,deleteTask,fetchTodoList,updateCompleted } = todoStore;
const showModal = ref(false);
const form = reactive({
    'task': '',
    'description': '',
    'due_date': '',
})
const clearForm = () => {
    form.task = '';
    form.description = '';
    form.due_date = '';
}

const updatedTask = async (id) => {
    await updateCompleted(id);
    await fetchTodoList();
}
const removeTask = (id) => {
    deleteTask(id);
    fetchTodoList();
}
const addTasks = async () => {
    await addTask(form);
    closeModal();
    clearForm();
    await fetchTodoList();
}
onMounted(async () => {
    await fetchTodoList();
    console.log('todolist==>', loading.value)
});

const closeModal = () => {
    showModal.value = false;

    // Ensure Bootstrap removes the backdrop
    const modalBackdrop = document.querySelector('.modal-backdrop');
    if (modalBackdrop) {
        modalBackdrop.remove();
    }

    // Remove Bootstrap's modal-open class from <body>
    document.body.classList.remove('modal-open');
};

</script>
<template>
    <AdminLayout>
        <div class="container">
            <div class="heading m-2 p-2 d-flex justify-content-between">
                <h1>Todo List</h1>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" @click="showModal = true" data-toggle="modal"
                    data-target="#storeTodoListModal">
                    <i class="fa fa-plus"> Add task</i>
                </button>
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
                            <td><button :disabled="loading" @click="updatedTask(item.id)"
                                    :class="[item.is_completed ? 'btn btn-warning' : 'btn btn-success']"><i
                                        :class="[item.is_completed ? 'fa fa-check-square' : 'fa fa-square']"></i> </button>
                            </td>
                            <td><button class="btn btn-danger" @click="removeTask(item.id)">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="showModal" class="modal fade" id="storeTodoListModal" tabindex="-1"
            aria-labelledby="storeTodoListModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="storeTodoListModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" @click="showModal = false"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="row" @submit.prevent="addTasks">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" v-model="form.task" name="task" class="form-control"
                                    placeholder="Task">
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="form.description" name="description" class="form-control"
                                    placeholder="Description">
                            </div>
                            <div class="form-group">
                                <input type="date" v-model="form.due_date" name="due_date" class="form-control"
                                    placeholder="start_time">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showModal = false"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>