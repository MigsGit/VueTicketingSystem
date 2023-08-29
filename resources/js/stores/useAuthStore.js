import { defineStore } from "pinia";
import Router from "../../routes/routes.js";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        username: null,
        name: null,
        access_token: null,
        error: null,
    }),
    getters: {
      
    },
    actions: {
        
        resetStore() {
            console.log("useAuthStore: resetStore");
            this.$reset();
        },
    },
    persist: true,
});
