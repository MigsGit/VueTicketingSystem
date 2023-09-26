<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Assigned Ticket</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <br><br>
                <table class="table table-sm table-bordered table-striped table-hover dt-responsive wrap" ref="tableAssingedTicket">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Ticket No.</th>
                            <th>Assigned To</th>
                            <th>Resolution Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <pre>{{ticketDetails}}</pre> -->
                        <tr v-for="row in ticketDetails" :key="row.id">
                            <td class="text-center">
                                <!-- <button type="button" class="btn btn-info btn-sm" :disabled="row.status != 0" @click="editTicket(row.id)"><i class="fas fa-edit"></i></button> -->
                                <button type="button" class="btn btn-info btn-sm" :disabled="row.status != 0" @click="fnClickClosingTicket(row.id)"><i class="fas fa-edit"></i></button>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning" v-if="row.status == 0">Pending</span>
                                <span class="badge bg-info" v-else-if="row.status == 1">Assigned</span>
                                <span class="badge bg-success" v-else-if="row.status == 3">Closed</span>
                            </td>
                            <td>{{ row.ticket_no }}</td>
                            <td>{{ row.assign_to }}</td>
                            <td>{{ row.resolution_timeime }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" ref="modalClosingTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Closing Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"  @submit.prevent="fnSaveClosingTicket()" ref="formClosingTicket">
                    <div class="modal-body">
                        <input type="text" v-model="frmClosingTicket.ticket_id"  class="form-control mb-3">
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text w-25 text-wrap" id="addon-wrapping">Initial Assessment Summary</span>
                            <textarea v-model="frmClosingTicket.initial_assessement_summary" type="text" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text w-25" id="addon-wrapping" style="text-align: center;">Root Cause</span>
                            <textarea v-model="frmClosingTicket.root_cause" type="text" class="form-control" rows="2"></textarea>
                        </div>

                        <fieldset class="border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3">Resolution Procedure:</legend>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <div class="input-group flex-nowrap mb-3 justify-content-md-start">
                                        <button
                                            class="btn btn-primary btn-sm"
                                            type="button"
                                            data-item-process="add"
                                            @click="fnNewResolution($event)"
                                        >
                                            <li class="fa fa-plus"></li>
                                            Add New
                                        </button>
                                    </div>
                                    <div class="input-group flex-nowrap mb-3">
                                        <select ref="searchResolutionTitle" class="form-select"  @change="selectedResolutionTitle($event)">
                                            <option disabled selected>Conformance Mode...</option>
                                            <option v-for="row in optResolutionTitle" :key="row.id" :value="row.id">{{ row.procedure_title }}</option>
                                        </select>
                                            <button
                                                class="btn btn-info btn-sm"
                                                type="button"
                                                data-item-process="search"
                                                @click="fnReadResolutionProcedureById()"
                                            >
                                            <li class="fa fa-search"></li> Search
                                            </button>
                                    </div>
                                </div>
                            <input class="form-control" v-model="frmClosingTicket.resolution_procedure_title_id" type="text" />
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in resolutionProcedureDetails" :key="row.id">
                                        <td>  </td>
                                        <td> {{ row.procedure_list }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Reference Link Used</span>
                            <input v-model="frmClosingTicket.reference_link" name="reference_link" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Datetime Resolved</span>
                            <input v-model="frmClosingTicket.date_time_resolved" type="datetime-local" class="form-control" aria-describedby="addon-wrapping">
                            <span class="input-group-text" id="addon-wrapping">Datetime Conformance</span>
                            <input v-model="frmClosingTicket.date_time_closed" type="datetime-local" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <label class="input-group-text mb-3" for="inputGroupSelect01">Confirmed closure by the requestor? if yes, indicate date, and time of conformance </label>
                        <div class="input-group mb-3">
                            <select v-model="frmClosingTicket.is_close" class="form-select" id="inputGroupSelect01">
                                <option disabled selected>Choose...</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                            </select>
                            <select v-model="frmClosingTicket.conformance_mode" class="form-select">
                                <option disabled selected>Conformance Mode...</option>
                                <option value="1">Verbal</option>
                                <option value="2">Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--

-->
    <div class="modal fade" ref="modalNewResolution" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header  bg-secondary">
                    <h5 class="modal-title" id="staticBackdropLabel">New Resolution Procedure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"  @submit.prevent="frmNewResolution()" ref="formClosingTicket">
                    <div class="modal-body">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                            <button class="btn btn-primary btn-sm" type="button" data-item-process="add" @click="fnAddRowResolution($event)">
                                &nbsp;<li class="fa fa-plus"></li>&nbsp;
                            </button>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Resolution Title</span>
                            <input v-model="procedureTitle" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2" ref="inputGroupResolutionList" v-for="(item, index) in inputCount.key_num" :key="index">
                            <!-- <pre>key_num:{{inputCount.key_num}}</pre> -->
                            <!-- <pre>item:{{item}}</pre> -->
                            <!-- <pre>index:{{index}}</pre> -->
                            <span class="input-group-text" ref="countResolution" for="key_num">{{ index+1 }}</span>
                            <textarea v-model="item.valueNewResolution" type="text" class="form-control" rows="1" id="key_num"></textarea>
                            <button class="btn btn-danger btn-sm" type="button" data-item-process="add" @click="fnRemoveRowResolution(index)">
                                &nbsp;<li class="fa fa-trash"></li>&nbsp;
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, reactive, onMounted, watch, nextTick} from 'vue'
import Swal from 'sweetalert2'

/* 
    Global Inputs
*/
    let objModalClosingTiket = ''
    let objModalNewResolution = ''

    const modalClosingTicket = ref(null)
    const modalNewResolution = ref(null)

    const ticketDetails = ref(null)
    const searchResolutionTitle = ref(null)
    const frmClosingTicket = ref({});

    const optResolutionTitle = ref(null)
    const selectedResolutionTitleId = ref(null)
    const resolutionProcedureDetails = ref(null)

    const tableAssingedTicket = ref(null)

/* 
    Multiple Inputs 
*/
    const inputCount = reactive({key_num: [] })
    const procedureTitle = ref(null)
/* 
    DataTable
*/
    var dtAssingedTicket = null;
    dtAssingedTicket = $(tableAssingedTicket.value).DataTable({

    });
/*
    * WATCH will reload the DataTable after saving.
    * This will serve as .draw()
*/
    onMounted(() => {
        getTicket()
        objModalClosingTiket = new Modal(modalClosingTicket.value)
        objModalNewResolution = new Modal(modalNewResolution.value)
    })
    watch(ticketDetails, async (ticketDetails) => {
        console.log(ticketDetails);
        dtAssingedTicket.destroy();
        nextTick(() => {
            dtAssingedTicket = $(tableAssingedTicket.value).DataTable()
        });
    })
    async function fnAddRowResolution(){
        inputCount.key_num.push({ valueNewResolution: []})
        console.log(inputCount.key_num);
    }

    async function fnRemoveRowResolution(index){
        inputCount.key_num.splice(index,1);
        console.log(inputCount);
    }

    async function selectedResolutionTitle(event){
        selectedResolutionTitleId.value = event.target.value;
    }
    async function getTicket(){
        try{
            let respose = await axios.get('/api/get_assigned_tickets')
            ticketDetails.value = respose.data
        }catch(err){
            alert(err)
        }
    }

    async function fnClickClosingTicket(ticketId){
        try{
            frmClosingTicket.value.ticket_id = ticketId;
            objModalClosingTiket.show()
            fnReadResolutionProcedureByUser()
        }catch(err){
            alert(err)
        }
    }

    async function fnNewResolution(){
        objModalNewResolution.show();
    }

    async function fnSaveClosingTicket(){
        try {
            let response = axios.post('/api/closing_ticket',frmClosingTicket.value)
            objModalClosingTiket.hide()
            Swal.fire({
                    icon: "success",
                    title: "Closed Successfully",
                    showConfirmButton: false,
                    timer: 1500,
            });
            getTicket()
        } catch (error) {
            alert('fnSaveClosingTicket')
        }
    }
    async function fnReadResolutionProcedureById(){
        let response = await axios.get('/api/read_resolution_title_by_id',{
            params: {selected_resolution_title_id: selectedResolutionTitleId.value}
        });
        let data = response['data'][0]
        resolutionProcedureDetails.value = data.resolution_procedure_lists
        frmClosingTicket.value.resolution_procedure_title_id = data.id
    }

    async function fnReadResolutionProcedureByUser(){
        let response = await axios.get('/api/read_resolution_by_user')
        optResolutionTitle.value = response['data']
        // console.log('optResolutionTitle',optResolutionTitle.value)
    }

    async function frmNewResolution(){
        try {
            let respose = await axios.post('/api/create_new_resolution',{inputCount,procedure_title: procedureTitle.value})
            objModalNewResolution.hide()
            let data = respose['data']

            frmClosingTicket.value.resolution_procedure_title_id = data['resolution_procedure_title_id']
            resolutionProcedureDetails.value = data['resolution_procedure_lists'][0]['resolution_procedure_lists']
            fnReadResolutionProcedureByUser()
        } catch (error) {
            console.log(error)
        }
    }


</script>

<style lang="scss" scoped>

</style>
