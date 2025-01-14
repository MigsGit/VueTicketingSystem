<template>
    <div class="container-fluid px-4">
        <div class="card mt-5"  style="width: 100%;">
            <div class="row">
                <h1 class="mt-4">Ticket</h1>
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
</script>

<style lang="scss" scoped>

</style>
