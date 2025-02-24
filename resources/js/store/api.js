import axios from "axios";

const adminToken = localStorage.getItem("admin-token");
if (!adminToken) throw new Error("Admin token not found");
const api = axios.create({
    baseURL: '/api/V1',
    headers: {
        "Content-Type": 'application/json',
        "Authorization": `Bearer ${adminToken}`
    }
});

export default api;