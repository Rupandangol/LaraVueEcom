<script setup>
import axios from 'axios';
import { onMounted, ref, TransitionGroup } from 'vue';
import Echo from 'laravel-echo';

const messages = ref([]);
const newMessage = ref('');
const isSending = ref(false);
const getMessages = async () => {
    const headers = {
        Authorization: `Bearer ${localStorage.getItem('user-token')}`
    }
    await axios.get('/api/V1/public-chat', { headers }).then((res) => {
        if (res.data.status == 'success') {
            messages.value = res.data.data;
        }
    });
}
const sendMessage = async () => {
    isSending.value = true;
    const data = {
        'message': newMessage.value
    }
    const headers = {
        Authorization: `Bearer ${localStorage.getItem('user-token')}`
    }
    await axios.post(`/api/V1/public-chat`, data, { headers }).then((res) => {
        if (res.data.status == 'success') {
            newMessage.value = "";
        }
    }).catch((e) => {
        console.log(e);
    });
    isSending.value = false;
}
// Format the timestamp to a readable format
const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
onMounted(() => {
    getMessages();
    window.Echo.channel('public-chat')
        .listen('.public.chat.sent', (e) => {
            messages.value.push(e.message); // Add the new message to the chat
        });

})
</script>
<template>
    <section class="chat-container">
        <div class="messages">
            <div v-for="message in messages" :key="message.id" class="message">
                <div class="message-content">
                    <p><code>{{ message.user_id }} <br></code>{{ message.message }}</p>
                    <span class="timestamp">{{ formatTimestamp(message.created_at) }}</span>
                </div>
            </div>
        </div>

        <div class="input-container">
            <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
            <button :disabled="isSending" @click="sendMessage">Send</button>
        </div>
    </section>
</template>
<style scoped>
.chat-container {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.message {
    margin: 5px 0;
    display: flex;
    justify-content: flex-start;
}

.my-message {
    justify-content: flex-end;
}

.message-content {
    max-width: 70%;
    padding: 10px;
    border-radius: 10px;
    background-color: #f1f1f1;
}

.my-message .message-content {
    background-color: #007bff;
    /* Change color for user's messages */
    color: white;
}

.timestamp {
    font-size: 0.8em;
    color: gray;
}

.input-container {
    display: flex;
    margin-top: 10px;
}

input {
    flex: 1;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-right: 5px;
}

button {
    padding: 10px 15px;
    border-radius: 5px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
}
</style>