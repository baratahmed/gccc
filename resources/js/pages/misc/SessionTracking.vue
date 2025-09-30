<script setup>
import { onMounted, reactive, ref} from "vue";
import {storeToRefs} from "pinia"
import LocalModal from "@/common/components/LocalModal.vue";
import { useSession, useNotification } from "@/stores";
const pinia_session = useSession();
const notify = useNotification();
const {loading} = storeToRefs(pinia_session); 
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"


const form = reactive({
    id: null,
    name: '',
    type: "1ST_YEAR",
});
const createStatus = ref(false);
const editStatus = ref(false);

onMounted(async()=>{
  await pinia_session.index()
  const [start, end] = pinia_session.last_session.name.split("-").map(Number);
  form.name = `${start + 1}-${end + 1}`;
})

const singleDelete = (id) => {
  Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then(async(result) => {
      if (result.isConfirmed) {
        const resData = await pinia_session.destroy(id);
        if(resData?.status){
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
          });
        }else{
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
          });
        }
      }
  });
}

const closeModal = (data) => {
    form.id = null
    pinia_session.errors = {}
    if(data == 'create'){
      createStatus.value = false
    }else{
      editStatus.value = false
    }
}

const openModal = (data,item) => {
    if(data == 'create'){
      createStatus.value = true
      const [start, end] = pinia_session.last_session.name.split("-").map(Number);
      form.name = `${start + 1}-${end + 1}`;
      form.type = "1ST_YEAR"
    }else{
      editStatus.value = true
      form.id = item.id
      form.name = item.name
      form.type = item.type
    }
}

const storeSession = async () => {
  const resData = await pinia_session.store(form)
  if(resData?.status){
    await pinia_session.index()
    notify.Success(resData.message);
    closeModal('create');
  }else{
    notify.Error("Something went wrong!");
  }
}

const updateSession = async () => {
  const resData = await pinia_session.update(form)
  if(resData?.status){
    await pinia_session.index()
    notify.Success(resData.message);
    closeModal('edit');
  }else{
    notify.Error("Something went wrong!");
  }
}

</script>

<template>

  <LocalModal :visible="createStatus" @close="closeModal('create')">
    <template #title>
        Create New Session
    </template>
    <form @submit.prevent="storeSession">
      <div class="modal-body">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model="form.name" placeholder="Session Name" :disabled="true">
            <span class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</span>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select v-model="form.type" id="type" class="form-control">
                <option value="1ST_YEAR">1ST YEAR</option>
                <option value="2ND_YEAR">2ND YEAR</option>
                <option value="HSC">HSC</option>
            </select>
            <span class="text-danger" v-if="errors?.type">{{ errors.type[0] }}</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">
          Save changes <span v-show="loading" class="spinner-border spinner-border-sm mr-1"></span>
        </button>
      </div>
    </form>
  </LocalModal>

  <LocalModal :visible="editStatus" @close="closeModal('edit')">
    <template #title>
        Edit Session
    </template>
    <form @submit.prevent="updateSession">
      <div class="modal-body">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model="form.name" placeholder="Session Name" :disabled="true">
            <span class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</span>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select v-model="form.type" id="type" class="form-control">
                <option value="1ST_YEAR">1ST YEAR</option>
                <option value="2ND_YEAR">2ND YEAR</option>
                <option value="HSC">HSC</option>
            </select>
            <span class="text-danger" v-if="errors?.type">{{ errors.type[0] }}</span>
        </div>
      </div>
      <div class="modal-footer">       
        <button class="btn btn-primary" type="submit">
          Update changes <span v-show="loading" class="spinner-border spinner-border-sm mr-1"></span>
        </button>
      </div>
    </form>
  </LocalModal>

   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">All Academic Sessions </h4>
          </div>
          <div class="col-12">
            <div v-if="loading" class="text-center m-5">
                <span
                    class="spinner-border spinner-border-sm mr-1 text-dark"
                    style="padding: 12px; font-size: 20px;"
                ></span>
                <div>Loading....</div>
            </div>
            <div class="card" v-else>

              <div class="card-body">

                <div class="d-flex justify-content-first mb-2">             

                    <div class="pb-3">
                        <button @click.prevent="openModal('create')" class="btn btn-sm btn-primary py-2">Add Session (+)</button>
                    </div>
                </div>


                <Table v-if="pinia_session.getItems?.data?.length > 0">
                    <template #tableHead>                       
                        <TableHead>Session Name</TableHead>
                        <TableHead>Type</TableHead>                  
                        <TableHead>Action</TableHead>
                    </template>
                    <TableRow v-for="(session,index) in pinia_session.getItems?.data" :key="index">
                        <TableData class="text-secondary"><b>{{session.name}}</b></TableData>
                        <TableData class="text-secondary"><b>{{session.type}}</b></TableData>
                        <TableData>
                            <div class="d-flex" style="gap:4px">                               
                                <PrimaryButton type="button" @click.prevent="openModal('edit',session)">
                                    <i class="fa fa-edit"></i>
                                </PrimaryButton>
                                <DeleteButton type="button" @click="singleDelete(session?.id)">
                                    <i class="fa fa-trash"></i>
                                </DeleteButton>
                            </div>
                        </TableData>
                    </TableRow>
                </Table>
                <div class="col-12" v-else> 
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>             

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
</template>

<style scoped>

</style>
