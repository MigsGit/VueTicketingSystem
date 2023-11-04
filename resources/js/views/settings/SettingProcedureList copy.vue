<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Resolution Procedure List</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <table class="table" ref="tableProcedureList">
                    <thead>
                        <tr>
                            <th style="width: 10%;">
                                Title
                            </th>
                            <th style="width: 10%;">
                                List
                            </th>
                            <th style="width: 10%;">
                                <li class="fas fa-cogs"></li>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in columnResolutionDetails" :key="row.resolution_procedure_lists">
                            <td>{{ row.procedure_title }}</td>
                            <td>
                                <p v-for="list in row.resolution_procedure_lists">
                                {{ list.procedure_list }}
                                </p>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-secondary dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        Action
                                    </button>
                                    <ul
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenu2"
                                    >
                                        <li>
                                            <a
                                                class="dropdown-item"
                                                href="#"
                                                ref="editPage"
                                                data-item-process="edit"
                                                :data-item-id="row.id"
                                            >Edit
                                            </a>
                                            <a
                                                class="dropdown-item"
                                                href="#"
                                                ref="editPage"
                                                data-item-process="view"
                                                :data-item-id="row.id"
                                            >View
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- <button class="btn btn-primary btn-sm">Edit</button>
                                <button class="btn btn-primary btn-sm">View</button> -->
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, reactive, onMounted, watch, nextTick} from 'vue'

    const tableProcedureList = ref("")
    const optResolutionTitle = ref("")
    const selectedResolutionTitleId = ref([])
    const columnResolutionDetails = ref([])
    const countResolutionList = ref([])
    var arrResolutionId = []

    onMounted(() => {
        fnReadResolutionProcedureByUser()
    })

    async function fnReadResolutionProcedureByUser(){
        let response = await axios.get('/api/read_resolution_by_user_setting')
        columnResolutionDetails.value = response['data'];
    }
</script>

<style lang="scss" scoped>

</style>
