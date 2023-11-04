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
    
    <Modal icon="fa-user" title="Resolution Procedure"> <!-- @add-event="" -->
        <template #body>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <button class="btn btn-primary btn-sm" type="button" data-item-process="add" @click="fnAddRowResolution($event)">
                    &nbsp;<li class="fa fa-plus"></li>&nbsp;
                </button>
            </div>
            <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping">Resolution Title</span>
                <input v-model="procedureTitle" type="text" class="form-control" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2" ref="inputGroupResolutionList" v-for="(item, index) in inputCount.key_num" :key="index" >
                <!-- <pre>key_num:{{inputCount.key_num}}</pre> -->
                <!-- <pre>item:{{item}}</pre> -->
                <!-- <pre>index:{{index}}</pre>  -->
                <label class="input-group-text" ref="countResolution" :for="index">{{ index+1 }}</label>
                <textarea v-model="item.valueNewResolution" type="text" class="form-control" rows="1" :id="index"></textarea>
                <button class="btn btn-danger btn-sm" type="button" data-item-process="add" @click="fnRemoveRowResolution(index)" :disabled="inputCount.key_num.length == 1">
                    &nbsp;<li class="fa fa-trash"></li>&nbsp;
                </button>
            </div>
        </template>
        <template #footer>
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </Modal>
</template>

<script setup>
    import {ref, reactive, onMounted, watch, nextTick} from 'vue'
    import DataTable from 'datatables.net-vue3'
    import DataTablesCore from 'datatables.net-bs5'
    DataTable.use(DataTablesCore)
    import Modal from '../../components/Modal.vue'

    import resolutionProcedure from '../../composables/resolution_procedure'
    const {fnAddRowResolution,fnRemoveRowResolution,inputCount,procedureTitle} = resolutionProcedure()

    onMounted(() => {
        document.querySelector('#modalSaveResProcedure').addEventListener('hidden.bs.modal', function () {
            inputCount.key_num = []
        })
    })

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
                        readResProcedure(resProcedureId);
                        console.log(resProcedureId);
                    });
                }
            },
        },
        { data: 'procedure_title',                  title: 'Title' },
        { data: 'resolution_procedure_lists',       title: 'List'  }
    ];
    async function readResProcedure(resProcedureId){
        let response = await axios.get('/api/read_resolution_title_by_id',{
            params: {selected_resolution_title_id: resProcedureId}
        });
        
        console.log(inputCount.key_num);
        // console.log(response.data[0].procedure_title);
        // console.log(response.data[0].resolution_procedure_lists.length);
        let arrResoProcedureList = response.data[0].resolution_procedure_lists
        for (let i = 0; i < arrResoProcedureList.length; i++) {
            const resProcedureList = arrResoProcedureList[i].procedure_list
            inputCount.key_num.push({ valueNewResolution: resProcedureList})
        }
    }
    

</script>

<style lang="scss" scoped>

</style>
