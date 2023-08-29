<script setup>
import { useAuthStore } from '../stores';
const useAuth = useAuthStore();
</script>
<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <!-- <a class="nav-link" href="index.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a> -->
                    <router-link class="nav-link" to="/dashboard">
                        <i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard
                    </router-link>
                <div class="sb-sidenav-menu-heading">Menu</div>
                    <router-link class="sb-nav-link-icon nav-link" to="user_master">
                        <i class="fas fa-user"></i>&nbsp; User Tables
                    </router-link>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ useAuth.name }}
        </div>
    </nav>
</template>
