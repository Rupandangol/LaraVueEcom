<script setup>
import axios from 'axios';
import { nextTick, onMounted, ref, TransitionGroup, watch } from 'vue';
import Echo from 'laravel-echo';
import AppNavbar from '../components/AppNavbar.vue';
import { Chart } from 'chart.js';

const messages = ref([]);
const newMessage = ref('');
const isSending = ref(false);
const userId = ref(null);
const messagesContainer = ref(null); // Reference for the messages container

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


const getUserIdFromToken = async () => {
    const token = localStorage.getItem('user-token');
    if (!token) {
        return ''; // Handle the case where the token is not present
    }
    try {
        const response = await axios.get('/api/V1/users-data', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        }).then((response) => {
            userId.value = response?.data?.data?.id;
        });
    } catch (error) {
        console.log(error);
    }
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
            scrollToBottom();
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
const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

// Watch for new messages and scroll to bottom after DOM update
watch(messages, async () => {
    await nextTick();
    scrollToBottom();
});

onMounted(() => {
    getUserIdFromToken();
    getMessages();
    window.Echo.channel('public-chat')
        .listen('.public.chat.sent', (e) => {
            messages.value.push(e.message); // Add the new message to the chat
            scrollToBottom();
        });

})
</script>
<template>
    <AppNavbar></AppNavbar>
    <section class="chat-container p-5">
        <div ref="messagesContainer" class="messages">
            <div v-for="message in messages" :key="message.id"
                :class="{ 'message my-message': message.user_id === userId, 'message': message.user_id !== userId }">
                <div class="message-content">
                    <p><code class="messenger">{{ message?.users?.name }} <br></code>{{ message.message }}</p>
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
    height: 90vh;
    overflow: auto;
}

.messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background-color: rgb(240, 240, 240);
}

.message {
    margin: 5px 0;
    display: flex;
    color: white;
    justify-content: flex-start;
}

.my-message {
    justify-content: flex-end;
}

.messenger {
    color: #41ffc7;
}

.message-content {
    background-color: #007bff;
    max-width: 70%;
    padding: 10px;
    border-radius: 10px;
}

.timestamp {
    font-size: 0.8em;
    color: whitesmoke;
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