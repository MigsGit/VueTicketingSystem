<script setup>
import { useRouter, useRoute  } from 'vue-router'

import { useAuthStore } from "../stores";

const infoLogin = {
    email : 'cdp@gmail.com',
    password : 'pmi1234',
};

const storeAuth = useAuthStore();

const sigin = async () => {
    storeAuth.login(infoLogin);
};
        
</script>
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

