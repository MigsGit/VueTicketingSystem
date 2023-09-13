<script setup>
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';

    DataTable.use(DataTablesCore);

    import { Modal } from "bootstrap";
    import { onMounted, ref, reactive, nextTick } from "vue";
    import $ from 'jquery';
    import api from '../../axios';

    const modalTicket = ref();
    const tableTicket = ref();
    const columns = ref();
    const ticketModal = ref();
    const ticketForm = reactive({
        id: "",
        subject: "",
        message: "",
    })
    var dt = '';
    const datatable = () => {
        dt = $(tableTicket.value).DataTable({
            "processing"    : true,
            "language": {
                "info": "Showing _START_ to _END_ of _TOTAL_ tickets",
                "lengthMenu": "Show _MENU_ tickets",
            },
        });
    };

    onMounted( async () => {
        // state.userModal = new Modal(modalUser.value, {});
        await getTicket();
        datatable();
        dt.destroy();

        nextTick(() => {
                datatable();
        })
        modalTicket.value.addEventListener('hidden.bs.modal', event => {
            // console.log('modalUser closed');

        });
    })

    const getTicket = async () => {
        await api.get('api/get_tickets').then((res) => {
            // console.log(res.data);
            columns.value = res.data;
        }).catch((err)=>{

        });
    }

    const saveTicket = async () => {
        const formData = new FormData(ticketForm.value);

        await api.post('api/save_ticket', formData).then((res) => {
            getTicket();
        }).catch((err) => {

        });
    }
    const editTicket = async (ticketId) => {
        // console.log(ticketId);
        await api.get('api/get_ticket_info', { params: { id: ticketId } }).then((res) => {

        }).catch((err) => {

        });
    }

</script>
<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ticket</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">

                <button type="button" class="btn btn-primary" style="float: right !important;" data-bs-toggle="modal" data-bs-target="#ModalTicket" @click="ticketModal = 'Add Ticket'"><i class="fas fa-plus"></i> Add Ticket</button>
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
                        <tr v-for="row in columns" :key="row.id">
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm" :disabled="row.status != 0" @click="editTicket(row.id)"><i class="fas fa-edit"></i></button>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning" v-if="row.status == 0">Pending</span>
                                <span class="badge bg-info" v-else-if="row.status == 1">Assigned</span>
                            </td>
                            <td>{{ row.ticket_no }}</td>
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
                        <h4 class="modal-title"><i class="fa-brands fa-d-and-d"></i> {{ ticketModal }}</h4>
                        <button type="button" class="btn-close" id="closebtn" data-item-process="create" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" @submit.prevent="saveTicket()" ref="forms" autocomplete="off">
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
                                <input type="text" class="form-control" name="ticket_subject" v-model="ticketForm.subject">
                            </div>
                            <br>
                            <div class="form-group">
                                <label><strong>Message:</strong></label>
                                <!-- <input type="text" class="form-control" name="ticket_subject" v-model="ticketForm.subject"> -->
                                <textarea class="form-control" name="ticket_message" v-model="ticketForm.message" cols="30" rows="10" placeholder="Type Here..."></textarea>
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
