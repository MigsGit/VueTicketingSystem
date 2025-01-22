<template>
    <div class="container-fluid px-4">
        <div class="card mt-5"  style="width: 100%;">
            <div class="row">
                <h1 class="mt-4">Ticketss</h1>
            </div>
            <div class="card-body overflow-auto">
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
</template>

<script setup>
    // await axios.get('/check_ses').then((res) => {
    //         // console.log(res);
    //         columns.value = res.data;
    // }).catch((err) => {
    //     // console.log(err);
    // });
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
</script>

<style lang="scss" scoped>

</style>
