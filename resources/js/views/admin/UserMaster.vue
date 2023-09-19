<template>
    <div class="container-fluid px-4">
        <h1 class="mt-4">User Master</h1>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="row mb-3">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button
                            class="btn btn-primary btn-sm"
                            type="button"
                            data-item-process="add"
                            @click="fnClckUserInfo($event)"
                        >
                            <li class="fa fa-plus"></li>
                            Add New
                        </button>
                    </div>
                </div>
                <table class="table" ref="tableUserInfo">
                    <thead>
                        <tr>
                            <th style="width: 10%;">

                            </th>
                            <th style="width: 10%;">
                                Name
                            </th>
                            <th style="width: 10%;">
                                Email
                            </th>
                            <th style="width: 10%;">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in getUserInfo" :key="row.id">
                            <td> {{ row.id }} </td>
                            <td> {{ row.name }} </td>
                            <td> {{ row.email }} </td>
                            <td>
                                <button type="button" class="btn btn-primary" :data-item-id="row.id"  @click="fnClckUserInfo($event)">
                                    Edit
                                </button>
                            </td>
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
    <div class="modal fade" ref="modalUserInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"  @submit.prevent="fnSaveUserInfo()" ref="formUser">
                    <div class="modal-body">
                        <input type="hidden" ref="data_id" v-model="frmUserInfo.data_id" class="form-control mb-3" placeholder="ID">
                        <input type="text" ref="full_name" v-model="frmUserInfo.full_name" class="form-control mb-3" placeholder="Full Name">
                        <input type="text" ref="email" v-model="frmUserInfo.email" class="form-control mb-3" placeholder="Email">
                        <!-- <input type="text" ref="password" v-model="frmUserInfo.password" class="form-control mb-3" placeholder="Password"> -->
                        <!-- <input type="text" ref="confirm_password" v-model="frmUserInfo.confirm_password" class="form-control mb-3" placeholder="Confirm Password"> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {onMounted, ref, reactive, inject , watch, nextTick} from 'vue'
    import DataTable from 'datatables.net-vue3'
    import DataTablesCore from 'datatables.net-bs5'
    import Swal from 'sweetalert2'
    DataTable.use(DataTablesCore)

    var fnDtUserInfo = ''
    let objModalUserInfo

    const getUserInfo = ref("")
    const modalUserInfo = ref("")
    const frmUserInfo = ref({})
    const formUser = ref("");
    const full_name = ref('')
    const email = ref('')
    const tableUserInfo = ref('')

    // const swal = inject('$swal')
    async function fnFetchUserInfo(){
        let response = await axios.get('/api/get_user_info');
        getUserInfo.value = response.data.data;
    }
    async function fnClckUserInfo(event){
        event.preventDefault()
        objModalUserInfo.show();
        const dataId = event.target.dataset.itemId;
        if(dataId == null){
            console.log('dataId');
        }else{
            let response = await axios.get('/api/read_user_info',{
            params:{data_id : dataId}
            });
            let data = response.data;
            let status = response.status;

            if(status === 200 ){
                frmUserInfo.value.data_id = data.id
                frmUserInfo.value.full_name = data.name
                frmUserInfo.value.email = data.email
                console.log(response.status)
            }
        }
    }
    async function fnSaveUserInfo(){
        try {
            let response = await axios.post('/api/save_user_info',frmUserInfo.value)
            // console.log(response)
            objModalUserInfo.hide();
            Swal.fire({
                    icon: "success",
                    title: "Saved Successfully",
                    showConfirmButton: false,
                    timer: 1500,
            });
            fnFetchUserInfo()
        }
        catch (err) {
            let errorStatus = err.response.status
            let errorMessage = err.response.data.errors
            console.log(err);
            if(errorStatus === 422){
                if(frmUserInfo.value.full_name){
                    full_name.value.classList.remove('is-invalid')
                    full_name.value.title = "";
                }else{
                    full_name.value.classList.add('is-invalid')
                    full_name.value.title = errorMessage.full_name[0];
                }
                if(frmUserInfo.value.email){
                    email.value.classList.remove('is-invalid')
                    email.value.title = "";
                }else{
                    email.value.classList.add('is-invalid')
                    email.value.title = errorMessage.email[0];
                }
            }else{
                alert('Invalid Input ! ')
            }
        }
    }

    fnDtUserInfo =    $(tableUserInfo.value).DataTable({
        "processing"    : true,
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ tickets",
            "lengthMenu": "Show _MENU_ tickets",
        },
    })
    onMounted(() => {
        fnFetchUserInfo()
        objModalUserInfo = new Modal(modalUserInfo.value)
        modalUserInfo.value.addEventListener('hidden.bs.modal',function (){
            formUser.value.reset()
        })
    })
    watch(getUserInfo, async (getUserInfo) => {
        fnDtUserInfo.destroy();
        nextTick(() => {
            fnDtUserInfo =    $(tableUserInfo.value).DataTable({
                "processing"    : true,
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ tickets",
                    "lengthMenu": "Show _MENU_ tickets",
                },
            })
        });
    })
</script>
<style lang="scss" scoped>
@import "datatables.net-bs5";
</style>

