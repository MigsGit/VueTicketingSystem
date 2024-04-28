<template>
    <div class="container-fluid px-4">
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Ticket</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'ticket' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="fa-user" />&nbsp;User Master</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'user_master' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="fa-cog" />&nbsp;TRT Maintenance</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'trt_maintenance' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="fa-tasks" />&nbsp;Procedure List</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'setting_procedure_list' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    // await axios.get('/check_ses').then((res) => {
    //         // console.log(res);
    //         columns.value = res.data;
    // }).catch((err) => {
    //     // console.log(err);
    // });
</script>

<style lang="scss" scoped>

</style>
