
<script setup>
    import DataTable from 'datatables.net-vue3'
    import DataTablesCore from 'datatables.net-bs5'

    DataTable.use(DataTablesCore)
    import $ from 'jquery';
    import { ref, watch, onMounted, nextTick } from "vue";
    import { reactive, computed } from "vue";

    const columns = ref();
    const tableTRT = ref();
    const state = reactive({
        trtModal: null,
        trtModalTitle : "",
    })
    let t = 0;
    const ModalTRT = ref();
    const trtForm = ref();
    const trtFormInitialVal = reactive({
        id: "",
        code: "",
        description: "",
        days: 0,
        hours: 0
    })
    const trtFormInputs = reactive({ ...trtFormInitialVal });
    const toastr = new Toast();
    // const swal = new Swal();

    var dt = null;
    dt = $(tableTRT.value).DataTable({});

    onMounted( async () => {
        await getTRT();
        state.trtModal = new Modal(ModalTRT.value, {});

        ModalTRT.value.addEventListener('hidden.bs.modal', event => {
            console.log('ModalTRT closed');
            Object.assign(trtFormInputs,trtFormInitialVal)
        });
    });


    watch(columns, async (columns) => {
        console.log(columns);
        dt.destroy();
        nextTick(() => {
            dt = $(tableTRT.value).DataTable()
        }); 
    });

    const getTRT = async () => {
        // console.log('qwe');
        await axios.get('/api/get_trt').then((res) => {
            // console.log(res);
            columns.value = res.data;
        }).catch((err) => {
        });
    }
    const saveTrt = async () => {
        let formData = new FormData(trtForm.value);
        // console.log(csrf);
        await axios.post('/api/save_trt', formData).then((res) => {
            state.trtModal.hide();
            toastr.open({
                message: res.data.msg,
                type: 'success',
                position: 'top-right',
                duration: 2000,
            }); // * usage of Toastr notification
            getTRT();

        }).catch((err) => {

        });
    }
    const editTrt = async (id) => {
        await axios.get('/api/get_trt_for_edit', {params:{id:id}}).then((res) => {
            console.log(res);
            state.trtModal.show();
            trtFormInputs.id = res.data.id;
            trtFormInputs.code = res.data.code;
            trtFormInputs.description = res.data.description;
            trtFormInputs.hours = res.data.duration_hour;
            trtFormInputs.days = res.data.duration_day;

            getTRT();

        }).catch((err) => {
            
        });
    }

    const deactivateTrt = async (id,status) => {
        await Swal.fire({
            title: status,
            text: `Are you sure you want to ${status} this?`,
            icon: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/api/deact_trt', {id:id, status: status}).then((res) => {
                    getTRT();

                }).catch((err) => {

                })
            }
        })
    }
</script>

<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Administrator</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <button type="button" class="btn btn-primary" style="float: right;" data-bs-toggle="modal" data-bs-target="#ModalTRT" @click="state.trtModalTitle = 'Add TRT'"><i class="fas fa-plus"></i> Add TRT</button><br><br>
                <table class="table table-sm table-bordered table-striped table-hover dt-responsive wrap" ref="tableTRT">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Code</th>
                            <th>Duration</th>
                            <th>Description</th>
                            <th>Created by</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in columns" :key="row.id">
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" style="margin-right: 3px;" @click="editTrt(row.id)"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" @click="deactivateTrt(row.id,'delete')" v-if="row.deleted_at == null"><i class="fas fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-success" @click="deactivateTrt(row.id,'active')" v-else><i class="fas fa-redo"></i></button>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success" v-if="row.deleted_at == null">Active</span>
                                <span class="badge rounded-pill bg-danger" v-else>Deactivated</span>
                            </td>
                            <td>{{ row.code }}</td>
                            <!-- <td>{{ row.duration }}</td> -->
                            <td>
                                {{ `${row.duration_day} Days : ${row.duration_hour} Hours` }}
                            </td>
                            <td>{{ row.description }}</td>
                            <td>{{ row.user_details.name }}</td>
                        </tr>
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>

    <!--
        MODAL
     -->
    <!-- Modal -->
    <div class="modal fade" id="ModalTRT" ref="ModalTRT" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ state.trtModalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form ref="trtForm" @submit.prevent="saveTrt()" autocomplete="off">
                    <div class="modal-body">
                            <input type="hidden" name="trtId" v-model="trtFormInputs.id">
                            <div class="form-group">
                                <label>TRT Code:</label>
                                <input type="text" class="form-control text-uppercase" name="code" v-model="trtFormInputs.code" >
                            </div>
                            <label>Duration:</label>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <!-- <label>Days</label>
                                        <input type="number" name="days" class="form-control" v-model="trtFormInputs.days"> -->
                                        <div class="input-group input-group-sm mb-3">
                                            <!-- <input type="number" name="days" class="form-control" v-model="trtFormInputs.days" placeholder="0"  min="0" max="31" :disabled="trtFormInputs.hours != 0" :disabled="disableInputDays()" required> -->
                                            <input type="number" name="days" class="form-control" v-model="trtFormInputs.days" placeholder="0"  min="0" max="31" :readonly="trtFormInputs.hours > 0" required>

                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1" style="background-color: #17a2b8; color: white;" >Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <input type="number" name="hours" class="form-control" v-model="trtFormInputs.hours" placeholder="0" min="0" max="23" :readonly="trtFormInputs.days > 0" required>
                                            <!-- <input type="number" name="hours" class="form-control" v-model="trtFormInputs.hours" placeholder="0" min="0" max="23" :disabled="inputStatus.hours" required> -->
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1" style="background-color: #17a2b8; color: white;">Hours</span>
                                            </div>
                                        </div>
                                        <!-- <label>Hours</label> -->
                                    </div>
                                </div>
                                <!-- <input type="text" class="form-control" name="code" v-model="trtFormInputs.duration"> -->
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <input type="text" class="form-control" name="description" v-model="trtFormInputs.description">
                            </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
@import "datatables.net-bs5";
</style>