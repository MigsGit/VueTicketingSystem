<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ticket</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <br><br>
                <table class="table table-sm table-bordered table-striped table-hover dt-responsive wrap" ref="tableTicket">
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
                                <button type="button" class="btn btn-info btn-sm" :disabled="row.status != 0" @click="closingTicket(row.id)"><i class="fas fa-edit"></i></button>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning" v-if="row.status == 0">Pending</span>
                                <span class="badge bg-info" v-else-if="row.status == 1">Assigned</span>
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
    <!-- Modal -->
    <!--
Close (Permanent)
Close (Work around)
Does not meet the requirements


A. DATE AND TIME RESOLVED:

>02-14-23 9:47am

--
B. INITIAL ASSESSMENT SUMMARY:

> Access to Special Acceptance Module for the specified users
--
C. ROOT CAUSE:

>No access to Special Acceptance Module

--

D. MATERIALS AND EQUIPMENT USED:

>N/A

--
F. RESOLUTION PROCEDURES:

1.Login to Rapid
2.Add access to Special Acceptance Module
3.Check the user's accounts if the new module is successfully created
4.
5.

--
G. REFERENCE LINKS USED:

>N/A

--

H. Confirmed Closure by Requestor (Y/N) - if yes, indicate conformance mode (verbal or email), date, and time of conformance

>N, Email

Setting for API ->
Close (Permanent)
Close (Work around)
Does not meet the requirements
     -->
    <div class="modal fade" ref="modalClosingTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"  @submit.prevent="fnClosingTicket()" ref="formClosingTicket">
                    <div class="modal-body">
                        <input type="text" name="" id="" class="form-control mb-3">
                        <!-- <div class="input-group flex-nowrap mb-3"> -->
                        <!-- </div> -->
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Initial Assessment Summary</span>
                            <textarea v-model="frmClosingTicket.initial_assessement_summary" type="text" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="input-group flex-nowrap mb-3">
                            <span class="input-group-text" id="addon-wrapping">Root Cause</span>
                            <textarea v-model="frmClosingTicket.root_cause" type="text" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <fieldset class="border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3">Resolution Procedure:</legend>
                            
                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <div class="input-group flex-nowrap mb-3 justify-content-md-start">
                                        <button
                                            class="btn btn-primary btn-sm"
                                            type="button"
                                            data-item-process="add"
                                            @click="fnSaveResolutionProcedure($event)"
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
                                                data-item-process="add"
                                                @click="fnReadResolutionProcedureById()"
                                            >
                                            <li class="fa fa-search"></li> Search
                                            </button>
                                    </div>
                                </div>
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
</template>

<script setup>
import {ref, reactive, onMounted} from 'vue';
    let objModalClosingTiket = ''

    const ticketDetails = ref(null)
    const modalClosingTicket = ref(null)
    const searchResolutionTitle = ref(null)
    const frmClosingTicket = ref({});

    const optResolutionTitle = ref(null);
    const selectedResolutionTitleId = ref(null);
    const resolutionProcedureDetails = ref(null);

    async function selectedResolutionTitle(event){
        selectedResolutionTitleId.value = event.target.value;
    }
    async function getTicket(){
        try{
            let respose = await axios.get('/api/get_tickets');
            ticketDetails.value = respose.data;
        }catch(err){
            alert(err)
        }
    }

    async function closingTicket(dataId){
        try{
            objModalClosingTiket.show();
            // console.log(dataId);

            // let respose = await axios.get('/api/read_tickets')
            // ticketDetails.value = respose.data
            // console.log(ticketDetails)
        }catch(err){
            alert(err)
        }
    }
    
    async function fnClosingTicket(){
        let response = axios.post('/api/closing_ticket',frmClosingTicket.value);
        console.log(response);
    }
    async function fnReadResolutionProcedureById(){
        let response = await axios.get('/api/read_resolution_title_by_id',{params: {selected_resolution_title_id: selectedResolutionTitleId.value}});
        resolutionProcedureDetails.value = response['data'][0].resolution_procedure_lists
        console.log(resolutionProcedureDetails.value.length);
    }

    async function fnReadResolutionProcedureByUser(){
        let response = await axios.get('/api/read_resolution_by_user');
        optResolutionTitle.value = response['data'];

        // resolution_procedure_lists
        // console.log(optResolutionTitle.value)
    }

    onMounted(() => {
        fnReadResolutionProcedureByUser()
        getTicket()
        objModalClosingTiket = new Modal(modalClosingTicket.value);
    })
</script>

<style lang="scss" scoped>

</style>
