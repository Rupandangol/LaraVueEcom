import { defineStore } from "pinia";
import api from "./api";

export const useWeatherStore = defineStore('weather', {
    state: () => ({
        weatherData: []
    }),
    actions: {
        async fetchWeather(place_id) {
            const response = await api.get(`/admin-fetch-weather/${place_id}`);
            if (response.data.status == 'success') {
                this.weatherData = response.data.data;
            }
        }
    }

})