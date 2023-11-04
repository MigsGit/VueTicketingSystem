<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Resolution Procedure List</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <DataTable
                        :columns="columns"
                        class="table table-striped table-responsive mt-2"
                        ajax="/api/read_resolution_by_user_setting"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                        ref="tableProcedureList"
                ></DataTable>
            </div>
        </div>
    </div>
    <Modal>
        <template>

        </template>
    </Modal>
</template>

<script setup>
    import {ref, reactive, onMounted, watch, nextTick} from 'vue'
    import DataTable from 'datatables.net-vue3'
    import DataTablesCore from 'datatables.net-bs5'
    DataTable.use(DataTablesCore)

    import Modal from '../../components/Modal.vue'
/*
    Global Inputs
*/
    const columns =[
        {
            data: 'action',
            title: 'Action',
            orderable: false,
            searchable: false,
            createdCell(cell) {
                let btnEditResProcedure = cell.querySelector("#editResProcedure")

                if((btnEditResProcedure !== null)){
                    btnEditResProcedure.addEventListener('click', function(){
                        let resProcedureId = this.getAttribute('data-id')
                        console.log(resProcedureId);
                    });
                }
            },
        },
        { data: 'procedure_title',                  title: 'Title' },
        { data: 'resolution_procedure_lists',       title: 'List'  }
    ];
</script>

<style lang="scss" scoped>

</style>
