// stores/mood.js
import axios from 'axios';
import { defineStore } from 'pinia';

export const useMoodStore = defineStore('mood', {
    // State (like data() in Vue)
    state: () => ({
        latestMood: ''
    }),

    // Actions (like methods, can be async)
    actions: {
        async addMood(mood) {
            this.isLoading = true;
            try {
                const adminToken = localStorage.getItem('admin-token');
                const response = await axios.post('/api/V1/admins-moods', {
                    mood: mood,
                    note: 'Created Through Pinia'
                }, {
                    headers: {
                        Authorization: `Bearer ${adminToken}`
                    }
                });
                console.log(response);
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },
        async getLatest() {
            try {
                const adminToken = localStorage.getItem('admin-token');
                const response = await axios.get('/api/V1/admins-moods-latest', {
                    headers: {
                        Authorization: `Bearer ${adminToken}`
                    }
                });
                if (response.data.status == 'success') {
                    this.latestMood = response.data.data.mood;
                }
                console.log('inside======>',this.latestMood);
            } catch (error) {
                console.log(error);
            }
        }
    },
});
