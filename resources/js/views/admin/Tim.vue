<template>
    <div class="container-fluid px-4">
        <div class="card mt-5"  style="width: 100%;">
            <h1 class="mt-4">Ticketing Management Dashboard</h1>
            <div class="card-body overflow-auto">
                <!-- <button type="button" class="btn btn-primary" style="float: right !important;" data-bs-toggle="modal" data-bs-target="#ModalTicket" @click="state.ticketModalTitle = 'Add Ticket'"><i class="fas fa-plus"></i> Add Ticket</button>
                <br><br> -->
                <DataTable
                        :columns="columns"
                        class="table table-striped table-responsive mt-2"
                        ajax="/vue-ticketing-system/api/get_tickets"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                        ref="tableTicket"
                >
                <thead>
                        <tr>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Ticket No.</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Assigned To</th>
                            <th>Resolution Time</th>
                        </tr>
                </thead>
                </DataTable>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalTicket" tabindex="-1" aria-labelledby="ModalTicketLabel" aria-hidden="true" ref="modalTicket">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa-brands fa-d-and-d"></i> {{ state.ticketModalTitle }}</h4>
                    <button type="button" class="btn-close" id="closebtn" data-item-process="create" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" @submit.prevent="saveTicket()" ref="formTicket" autocomplete="off">
                    <div class="modal-body" >
                        <input type="" v-model="ticketForm.ticketId">
                        <!-- <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1" style="background-color: #17a2b8; color: white;">Sub:</span>
                            </div>

                                <input type="text" class="form-control" name="packingCtrl" id="packingControlId" readonly>
                        </div> -->
                        <div class="form-group">
                            <label><strong>Subject:</strong></label>
                            <input type="text" class="form-control" v-model="ticketForm.subject" ref="subject" :readonly="state.ticketModalTitle === 'Assigning Ticket'" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label><strong>Message:</strong></label>
                            <textarea class="form-control" v-model="ticketForm.message" ref="message" cols="30" rows="10" placeholder="Type Here..." :readonly="state.ticketModalTitle === 'Assigning Ticket'" required></textarea>
                        </div>
                        <div class="form-group" v-show="state.ticketModalTitle === 'Assigning Ticket'" >
                            <label><strong>Assiged to: {{ state.ticketModalTitle }}</strong></label>
                            <MultiselectElement
                                v-model="ticketForm.assignedPerson"
                                mode="tags"
                                :close-on-select="false"
                                :searchable="true"
                                :options="assignedToOptions"
                                ref="assignedPerson"
                            />
                        </div>
                        <div class="form-group" v-show="state.ticketModalTitle === 'Assigning Ticket'" >
                            <label><strong>TRT:</strong></label>
                            <MultiselectElement
                                v-model="ticketForm.trtId"
                                :close-on-select="true"
                                :searchable="true"
                                :options="trtOptions"
                                ref="assignedPerson"
                            />
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary" v-show="modalViewDatas.buttonFunction == 'update'" >Save changes</button> -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    // TODO: Do the DataTable
    //TODO: Query for assigned ticket
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore);
    import { onMounted, ref, reactive, watch,nextTick } from "vue";
    import $ from 'jquery';
    import { useToast } from 'vue-toast-notification';
    const toastr = useToast();
    const modalTicket = ref();
    const tableTicket = ref();

    const state = reactive({
        ticketModal: null,
        ticketModalTitle: 'Add Ticket'
    })
    const formTicket = ref();
    const userRoles = ref();

    // const initialTicketForm = reactive({
    //     id: "",
    //     subject: "",
    //     message: "",
    // });
    // const ticketForm = reactive({ ...initialTicketForm });
    const ticketForm = ref({ });

    const columns =[
        {
            data: 'action',
            orderable: false,
            searchable: false,
            createdCell(cell) {
                let btnEditTicket = cell.querySelector("#btnEditTicket")
                let btnViewTicket = cell.querySelector("#btnViewTicket")
                if((btnEditTicket !== null)){
                    btnEditTicket.addEventListener('click', function(event){
                        event.preventDefault();
                        let ticketId = this.getAttribute('ticket-id')
                        let btnType = this.getAttribute('title')
                        editTicket(ticketId,btnType)
                    });
                }
                if((btnViewTicket !== null)){
                    btnViewTicket.addEventListener('click', function(event){
                        event.preventDefault();
                        let ticketId = this.getAttribute('ticket-id')
                        let btnType = this.getAttribute('title')
                        editTicket(ticketId,btnType)
                    });
                }
            },
        },
        { data: 'get_status'},
        { data: 'ticket_no'},
        { data: 'subject'},
        { data: 'message'},
        { data: 'assigned_to'},
        { data: 'resolution_time'}
    ];

    let assignedToOptions = reactive ([]);
    let trtOptions = ref ([]);

    onMounted( async () => {
        // await getTicket();
        state.ticketModal = new Modal(modalTicket.value, {});
        modalTicket.value.addEventListener('hidden.bs.modal', event => {
            console.log('modalUser closed');
            // ticketForm.assignedPerson = []
            // Object.assign(ticketForm, initialTicketForm);
        });
    })

    const saveTicket = async () => {

        await axios.post('api/save_ticket', ticketForm.value).then((res) => {
            if(res.data.result == 1){
                toastr.open({
                    message: res.data.msg,
                    type: 'success',
                    position: 'top-right',
                    duration: 2000,
                }); // * usage of Toastr notification
                tableTicket.value.dt.draw();
                console.log(tableTicket.value);
                console.log(tableTicket.value.dt);
                state.ticketModal.hide();
            }
        }).catch((err) => {

        });
    }
    const editTicket = async (ticketId,btnType) => {
        await axios.get('api/get_ticket_info', { params: { id: ticketId } }).then((res) => {
            let data = res.data.ticketData;
            // console.log(data);
            // return;
            state.ticketModal.show();

            /* full_name.value.classList.add('is-invalid')*/
            if(btnType === 'Edit'){
                state.ticketModalTitle = "Edit Ticket";
            }else{
                state.ticketModalTitle = "Assigning Ticket";
            }
            ticketForm.value.ticketId = data.id;
            ticketForm.value.subject = data.subject;
            ticketForm.value.message = data.message;
            ticketForm.value.trtId = data.trt_id;
            ticketForm.value.assignedPerson = res.data.assigned_to;
        }).catch((err) => {
            console.log(err);
            toastr.open({
                message: err.response.data.msg,
                type: 'error',
                position: 'top-right',
                duration: 2000,
            }); // * usage of Toastr notification
        });
    }
    const getAssignedToOption = async () => {
        await axios.get('api/get_user_option').then((res) => {

            let data = res.data.arr_user;
            assignedToOptions = data.map((value) => {
                return {
                    value: value.id,
                    label: value.name
                }
            });
        }).catch((err) => {
            console.log(err);
            toastr.open({
                message: err.response.data,
                type: 'error',
                position: 'top-right',
                duration: 2000,
            });
        });
    }

    const getTRTOption = async () => {
        await axios.get('api/get_trt_option').then((res) => {
            let data = res.data;

            // trtOptions.value = data.arr_trt.map((value) => {
            //     return {
            //         value: value.id,
            //         label: value.code
            //     }
            // });
            // console.log(assignedToOptions);
        }).catch((err) => {
            console.log(err);
            toastr.open({
                message: err.response.data,
                type: 'error',
                position: 'top-right',
                duration: 2000,
            });
        });
    }
    getAssignedToOption();
    getTRTOption();

</script>
<style  src="@vueform/multiselect/themes/default.css">
@import 'datatables.net-bs5';
</style>
