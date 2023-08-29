<template>
    <!-- <Topbar/> -->
    <Topbar/>
    <!-- Sidebar -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <Sidebar/>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <router-view></router-view>
            </main>
        </div>
    </div>
    <!-- Footer -->
    <Footers/>
    <!-- End of Footer -->
</template>

<script setup>
import Topbar from "../layouts/Topbar.vue";
import Sidebar from "../layouts/Sidebar.vue";
import Footers from "../layouts/Footer.vue";
</script>

<style lang="scss" scoped>

</style>
