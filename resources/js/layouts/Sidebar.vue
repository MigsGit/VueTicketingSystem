<script setup>
import { useAuthStore } from '../stores';
const useAuth = useAuthStore();
</script>
<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Interface</div>
                <router-link class="nav-link" :to="{ name: 'tim' }">
                    <font-awesome-icon class="nav-icon" icon="ticket" />&nbsp;TIM
                    <!-- <i class="fas fa-tachometer-alt" icon="fa-tachometer-alt"></i>&nbsp;Dashboard -->
                </router-link>
                <router-link class="nav-link" :to="{ name: 'dashboard' }">
                    <font-awesome-icon class="nav-icon" icon="gauge-high" />&nbsp;Dashboard
                    <!-- <i class="fas fa-tachometer-alt" icon="fa-tachometer-alt"></i>&nbsp;Dashboard -->
                </router-link>

                <div class="sb-sidenav-menu-heading">User</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'ticket' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Ticket
                </router-link>
                <div class="sb-sidenav-menu-heading">Admin</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'user_master' }">
                    <i class="fas fa-user"></i>&nbsp; User Tables
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'trt_maintenance' }">
                    <i class="fas fa-cog"></i>&nbsp; TRT Maitenance
                </router-link>
                <div class="sb-sidenav-menu-heading">ISS</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'assigned_ticket' }">
                    <font-awesome-icon class="nav-icon" icon="clipboard-list" />&nbsp; Assigned Ticket
                </router-link>

                <div class="sb-sidenav-menu-heading"><i class="fas fa-cog"></i> Settings</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'setting_procedure_list' }">
                    <i class="fas fa-tasks"></i>&nbsp; Procedure List
                </router-link>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ useAuth.name }}
        </div>
    </nav>
</template>
