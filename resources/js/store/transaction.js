import axios from "axios";
import { defineStore } from "pinia";
import api from "./api";
import Swal from "sweetalert2";

export const useTransactionStore = defineStore('transaction', {
    state: () => ({
        transactionData: [],
        meiliSearchResult: []
    }),
    actions: {
        async transactionImport() {
            const response = await api.post('/admin/transaction/import');
            if (response.status != 200) {
                Swal.fire(
                    response.response.data.status,
                    response.response.data.message)
            }
            if (response.data.status == 'success') {
                Swal.fire(
                    response.data.status,
                    response.data.message
                )
            }
        },
        async customMeiliSearch(params) {
            const response = await api.get('/admin/transaction/custom-search', { params });
            if (response.data.status == 'success') {
                const paginated = response.data.data;

                // Mimic the expected structure
                this.transactionData = {
                    transactions: paginated,
                };
            }
        }
    }
})