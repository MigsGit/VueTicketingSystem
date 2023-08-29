import { defineStore } from "pinia";
import Router from "../../routes/";
import axios from "axios";
import {useToastr} from '../components/toaster.js';
// import { useToast } from 'vue-toast-notification';
const toastr = useToastr();

export const useAuthStore = defineStore("auth", {
    state: () => ({
        email: null,
        name: null,
        access_token: null,
        error: null,
    }),
    getters: {
      
    },
    actions: {
        async login(credentials){
            console.log(credentials);
            // toastr.success("test"); // * usage of Toastr notification
            axios.post('/login',credentials).then((res)=>{
                console.log(res.data.userData);
                this.email = res.data.userData.email;
                this.name = res.data.userData.name;
                Router.push({name: 'dashboard'});
            })
            .catch((err)=>{

            });
        },
        async logout(){
            axios.get('/logout').then((res)=> {
                Router.push({name: 'login'});
                this.$reset();
            })
            .catch((err)=>{

            });
        },
        resetStore() {
            console.log("useAuthStore: resetStore");
            this.$reset();
        },
    },
    persist: true,
});
