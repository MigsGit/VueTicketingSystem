<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">User Master</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="row mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button
                            class="btn btn-primary btn-sm"
                            type="button"
                            @click="resolveRouteFromClick($event)"
                            data-item-process="add"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalView"
                        >
                            <li class="fa fa-plus"></li>
                            Add New
                        </button>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">

                            </th>
                            <th style="width: 10%;">
                                Name
                            </th>
                            <th style="width: 10%;">
                                Email
                            </th>
                            <th style="width: 10%;">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr v-for="row in dataFetchLinkList" :key="row.id"> -->
                        <tr>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
fetchUserInfo();
async function fetchUserInfo(){
    let response = await axios.get("api/get_user_info");
    console.log(response);

}

</script>

<style lang="scss" scoped>

</style>
