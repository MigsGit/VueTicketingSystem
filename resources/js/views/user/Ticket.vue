<script setup>
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    
    DataTable.use(DataTablesCore);

    import { onMounted, ref, reactive, watch,nextTick } from "vue";
    import $ from 'jquery';

    const modalTicket = ref();
    const tableTicket = ref();
    const columns = ref();
    // const ticketModalTitle = ref();
    const state = reactive({
        ticketModal: null,
        ticketModalTitle: 'Add Ticket'
    })
    const formTicket = ref();

    const initialTicketForm = reactive({
        id: "",
        subject: "",
        message: "",
    });
    const ticketForm = reactive({ ...initialTicketForm });

    import { useToast } from 'vue-toast-notification';
    const toastr = useToast();

    var dt = null;
    dt = $(tableTicket.value).DataTable({});

    onMounted( async () => {
        await getTicket();
        state.ticketModal = new Modal(modalTicket.value, {});
        modalTicket.value.addEventListener('hidden.bs.modal', event => {
            console.log('modalUser closed');
            formTicket.value.reset();
            Object.assign(ticketForm, initialTicketForm);
        });
    })
    /*
        * WATCH will reload the DataTable after saving.
        * This will serve as .draw()
    */
    watch(columns, async (columns) => {
        console.log(columns);
        dt.destroy();
        nextTick(() => {
            dt = $(tableTicket.value).DataTable()
        }); 
    });

    const getTicket = async () => {
        await axios.get('/api/get_tickets').then((res) => {
            // console.log(res.data);
            columns.value = res.data;
        }).catch((err)=>{

        });
    }

    const saveTicket = async () => {
        const formData = new FormData(formTicket.value);
        await axios.post('/api/save_ticket', formData).then((res) => {
            console.log(res);
            if(res.data.result == 1){
                toastr.open({
                    message: res.data.msg,
                    type: 'success',
                    position: 'top-right',
                    duration: 2000,
                }); // * usage of Toastr notification
                getTicket();
                state.ticketModal.hide();
            }
        }).catch((err) => {

        });
    }
    const editTicket = async (ticketId) => {
        // console.log(ticketId);
        await axios.get('/api/get_ticket_info', { params: { id: ticketId } }).then((res) => {
            console.log(res);
            state.ticketModal.show();
            state.ticketModalTitle = "Edit Ticket";

            ticketForm.id = res.data.ticketData.id;
            ticketForm.subject = res.data.ticketData.subject;
            ticketForm.message = res.data.ticketData.message;
        }).catch((err) => {

        });
    }

</script>
<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ticket</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <button type="button" class="btn btn-primary" style="float: right !important;" data-bs-toggle="modal" data-bs-target="#ModalTicket" @click="state.ticketModalTitle = 'Add Ticket'"><i class="fas fa-plus"></i> Add Ticket</button>
                <br><br>
                <table class="table table-sm table-bordered table-striped table-hover dt-responsive wrap" ref="tableTicket">
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
                    <tbody>
                        <tr v-for="row in columns" :key="row.id">
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm" :disabled="row.status != 0" @click="editTicket(row.id)"><i class="fas fa-edit"></i></button>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning" v-if="row.status == 0">Pending</span>
                                <span class="badge bg-info" v-else-if="row.status == 1">Assigned</span>
                                <span class="badge bg-success" v-else-if="row.status == 3">Closed</span>
                            </td>
                            <td>{{ row.ticket_no }}</td>
                            <td>{{ row.subject }}</td>
                            <td>{{ row.message }}</td>
                            <td>{{ row.assign_to }}</td>
                            <td>{{ row.res_time }}</td>
                        </tr>
                    </tbody>
                </table>
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
                            <input type="hidden" name="ticketId" v-model="ticketForm.id">
                            <!-- <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1" style="background-color: #17a2b8; color: white;">Sub:</span>
                                </div>
                                
                                    <input type="text" class="form-control" name="packingCtrl" id="packingControlId" readonly>
                            </div> -->
                            <div class="form-group">
                                <label><strong>Subject:</strong></label>
                                <input type="text" class="form-control" name="ticket_subject" v-model="ticketForm.subject" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label><strong>Message:</strong></label>
                                <!-- <input type="text" class="form-control" name="ticket_subject" v-model="ticketForm.subject"> -->
                                <textarea class="form-control" name="ticket_message" v-model="ticketForm.message" cols="30" rows="10" placeholder="Type Here..." required></textarea>
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

<style>
@import 'datatables.net-bs5';
</style>