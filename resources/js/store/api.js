import axios from "axios";

const adminToken = localStorage.getItem("admin-token");
// if (!adminToken) throw new Error("Admin token not found");
const api = axios.create({
    baseURL: '/api/V1',
    headers: {
        "Content-Type": 'application/json',
    }
});
export const setAuthToken = (token) => {
    if (token) {
        localStorage.setItem("admin-token", token);
        api.defaults.headers.Authorization = `Bearer ${token}`; // 👈 Update axios headers
    } else {
        localStorage.removeItem("admin-token");
        delete api.defaults.headers.Authorization; // 👈 Remove header on logout
    }
};

api.interceptors.request.use((config) => {
    const adminToken = localStorage.getItem("admin-token");
    if (adminToken) {
        config.headers.Authorization = `Bearer ${adminToken}`;
    }
    return config;
}, (error) => Promise.reject(error));

export default api;