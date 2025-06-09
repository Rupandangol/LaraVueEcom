import axios from "axios";
import { defineStore } from "pinia";
import api from "./api";
import Swal from "sweetalert2";

export const useTransactionStore = defineStore('transaction', {
    state: () => ({
        transactionData: []
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
        }
    }
})