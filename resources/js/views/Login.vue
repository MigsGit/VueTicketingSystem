<template>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <form class="user" @submit.prevent="sigin">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" v-model="infoLogin.email" ref="txtEmail" type="email" placeholder="name@example.com"/>
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" v-model="infoLogin.password" ref="txtPassword" type="password" placeholder="Password"/>
                                            <label for="inputPassword">Passwords</label>
                                            <div v-if="this.errorMsg != null" class="invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password.html">Forgot Password?</a>
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
import {inject} from 'vue'
import { useRouter, useRoute  } from 'vue-router'
    const infoLogin = {
        email : '',
        password : '',
    };
    const router = useRouter();

    async function sigin() {

            try {
                console.log('panel_admin')
                router.push("/dashboard")
                /* Serialized the form */
                let response = await axios.post("signin", this.infoLogin);
                let status = response.status;
                let userInfo = response.data.user;

                console.log(response);
                if(status == 200){
                    this.$store.dispatch("user", userInfo);
                    this.$router.push("/dashboard");
                }else{
                    alert('Invalid User. Please check the code');
                }
                // let route = this.$router.resolve({path: '/dashboard'}); //reload the page to dashboard
                // let route = this.$router.resolve('/link/to/page'); // This also works.
                // window.open(route.href, '_blank');
            } catch (error) {
                // let resultError = error.response.data.errors;
                // let invalidUserError = error.response;
                // // console.log(invalidUserError);
                // if(invalidUserError.status === 401){
                //     this.$refs.txtEmail.classList.add('is-invalid');
                //     this.$refs.txtPassword.classList.add('is-invalid');
                //     this.errorMsg  = invalidUserError.data.msg
                //     return false;
                // }
                // if(invalidUserError.status === 400){
                //     this.$refs.txtEmail.classList.add('is-invalid');
                //     this.$refs.txtPassword.classList.add('is-invalid');
                //     this.errorMsg  = "Bad Request: Server Failed";
                //     return false;
                // }else{
                //     if (!this.infoLogin.email) {
                //         this.$refs.txtEmail.classList.add('is-invalid');
                //         this.$refs.txtEmail.title = resultError.email[0];
                //     }else{
                //         this.$refs.txtEmail.classList.remove('is-invalid');
                //         this.$refs.txtEmail.classList.add('is-valid');
                //         this.$refs.txtEmail.title = "";
                //     }
                //     if (!this.infoLogin.password) {
                //         this.$refs.txtPassword.classList.add('is-invalid');
                //         this.$refs.txtPassword.title = resultError.password[0];
                //     }else{
                //         this.$refs.txtPassword.classList.remove('is-invalid');
                //         this.$refs.txtPassword.classList.add('is-valid');
                //         this.$refs.txtPassword.title = "";
                //     }
                // }
            }
        }
    // export default {

    //     name:"Login",
    //     data(){
    //         return {
    //             infoLogin:{
    //                 email:"migz@gmail.com",
    //                 password:"pmi12345",
    //             }, /** Serialized the login form */
    //             errorMsg : "",
    //         }
    //     },
    //     mounted() {
    //     },
    //     methods: {
    //         async sigin() {
    //             try {
    //                 /* Serialized the form */
    //                 let response = await axios.post("signin", this.infoLogin);
    //                 let status = response.status;
    //                 let userInfo = response.data.user;

    //                 console.log(response);
    //                 if(status == 200){
    //                     this.$store.dispatch("user", userInfo);
    //                     this.$router.push("/dashboard");
    //                 }else{
    //                     alert('Invalid User. Please check the code');
    //                 }
    //                 // let route = this.$router.resolve({path: '/dashboard'}); //reload the page to dashboard
    //                 // let route = this.$router.resolve('/link/to/page'); // This also works.
    //                 // window.open(route.href, '_blank');
    //             } catch (error) {
    //                 let resultError = error.response.data.errors;
    //                 let invalidUserError = error.response;
    //                 // console.log(invalidUserError);
    //                 if(invalidUserError.status === 401){
    //                     this.$refs.txtEmail.classList.add('is-invalid');
    //                     this.$refs.txtPassword.classList.add('is-invalid');
    //                     this.errorMsg  = invalidUserError.data.msg
    //                     return false;
    //                 }
    //                 if(invalidUserError.status === 400){
    //                     this.$refs.txtEmail.classList.add('is-invalid');
    //                     this.$refs.txtPassword.classList.add('is-invalid');
    //                     this.errorMsg  = "Bad Request: Server Failed";
    //                     return false;
    //                 }else{
    //                     if (!this.infoLogin.email) {
    //                         this.$refs.txtEmail.classList.add('is-invalid');
    //                         this.$refs.txtEmail.title = resultError.email[0];
    //                     }else{
    //                         this.$refs.txtEmail.classList.remove('is-invalid');
    //                         this.$refs.txtEmail.classList.add('is-valid');
    //                         this.$refs.txtEmail.title = "";
    //                     }
    //                     if (!this.infoLogin.password) {
    //                         this.$refs.txtPassword.classList.add('is-invalid');
    //                         this.$refs.txtPassword.title = resultError.password[0];
    //                     }else{
    //                         this.$refs.txtPassword.classList.remove('is-invalid');
    //                         this.$refs.txtPassword.classList.add('is-valid');
    //                         this.$refs.txtPassword.title = "";
    //                     }
    //                 }
    //             }
    //         },async fnIsTextOrPassword(){
    //             console.log('dasdsa');
    //         }
    //     }
    // }
</script>

