import axios from "axios";
import { createStore } from "vuex";

const store = createStore({
    state: {
        test: '123',
        cartCount: 0,
    },
    getters: {},
    mutations: {
        getCartCount() {
            axios.get('/api/V1/carts-count',
                {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('user-token')}`
                    }
                })
            .then((response) => {
                if (response.status == 200) {
                    console.log(response);
                    this.state.cartCount = response.data.count;
                }
            });
        }
    },
    actions: {
        getCartCount(context){
            context.commit('getCartCount');
        }
    }
});

export default store;