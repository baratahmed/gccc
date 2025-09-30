<script setup>
import { onMounted, ref, computed, reactive} from "vue";
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useRouter, useRoute } from "vue-router";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { useAssignment, useAuth, useNotification} from "@/stores";
import LocalModal from "@/common/components/LocalModal.vue";

const notify = useNotification();
const auth = useAuth();
const pinia_assignment = useAssignment();
const router = useRouter();
const route = useRoute();
const {loading} = storeToRefs(pinia_assignment);

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton, SuccessButton} from "@/common/components/Form"

onMounted(async()=>{
    await pinia_assignment.assignmentsByTopic(route.params.id, null)
})

const searchQuery = ref("");
const modal = reactive({
      id: null, // assignment id
      name: null,
      marks: null,
      comment: null,
});

const createMarkingStatus = ref(false)

const closeModal = () => {
  modal.id = null
  modal.marks = null
  modal.comment = null
  modal.name = null

  createMarkingStatus.value = false
 
}
const openModal = (assignment) => {
  modal.id = assignment.id
  modal.name = assignment.student.name
  modal.marks = assignment.marks
  modal.comment = assignment.comment
  createMarkingStatus.value = true
}

const updateMarking = async () => {
  const resData = await pinia_assignment.updateMarking(modal)
  if(resData?.status){
    await pinia_assignment.assignmentsByTopic(route.params.id, null)
    notify.Success(resData.message);
    modal.id = null
    modal.marks = null
    modal.comment = null
    modal.name = null
    closeModal();
  }else{
    notify.Error("Something went wrong!");
  }
}


const debounceSearch = computed(()=> debounce(getResults,500));

const getResults = async () => {
    await pinia_assignment.assignmentsByTopic(route.params.id, searchQuery.value)
}

</script>

<template>
  <LocalModal :visible="createMarkingStatus" @close="closeModal()">
    <template #title>
        Update value for ({{ modal.name }})
    </template>
    <form @submit.prevent="updateMarking">
      <div class="modal-body">
        <div class="form-group">
            <label for="marks">Mark</label>
            <input type="number" class="form-control" id="marks" v-model="modal.marks" placeholder="Assign Value">
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <input type="text" class="form-control" id="comment" v-model="modal.comment" placeholder="Write hidden comment">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="submit" :disabled="loading">Save</button>
      </div>
    </form>
  </LocalModal>

   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">Marking Assignments</h4>
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
                <div class="d-flex my-3">
                    <div class="mr-auto">
                        <div class="tb_search">
                            <input type="text" @input="debounceSearch" v-model="searchQuery" placeholder="Search by name..." class="form-control">
                        </div>
                    </div>
                </div>

                <Table v-if="pinia_assignment.getItems?.length > 0">
                    <template #tableHead>                       
                        <TableHead>Student Name</TableHead>
                        <TableHead>Roll</TableHead>
                        <TableHead>Marks</TableHead>
                        <TableHead>Comment</TableHead>                      
                        <TableHead>Action</TableHead>
                    </template>
                    <TableRow v-for="(assignment,index) in pinia_assignment.getItems" :key="index">
                        <TableData>{{assignment.student.name}}</TableData>
                        <TableData>{{assignment.student.roll_no}}</TableData>
                        <TableData class="text-success font-weight-bold">{{assignment.marks ? assignment.marks : 'Not set' }}</TableData>
                        <TableData class="text-secondary">{{assignment.comment ? assignment.comment : 'No Comment'}}</TableData>                                           

                        <TableData>
                            <div class="d-flex" style="gap:4px">
                                <SuccessButton type="button" @click.prevent="openModal(assignment)">
                                    <i class="fa fa-check"> Update</i>
                                </SuccessButton>
                                <photo-provider>
                                  <photo-consumer v-for="(file, index) in assignment.files" :intro="file.file_original_name" :key="file.id" :src="$filters.makeImagePath(file.file_name)">
                                    <PrimaryButton v-if="index === 0" type="button"><i class="fa fa-eye"> View Files</i></PrimaryButton>
                                  </photo-consumer>
                                </photo-provider>
                                
                                <!-- <DeleteButton type="button" @click="singleDelete(topic?.id)" v-if="auth.can('topic.delete')">
                                    <i class="fa fa-trash"></i>
                                </DeleteButton> -->
                            </div>
                        </TableData>
                    </TableRow>
                </Table>
                <div class="col-12" v-else> 
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>
                <Bootstrap5Pagination
                    :data="pinia_assignment.getItems"
                    @pagination-change-page="getResults"
                />

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
