<script setup>
import { onMounted, ref, computed, watch} from "vue";
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useRouter } from "vue-router";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { useCounselling, useBulkDelete, useSimple, useAuth } from "@/stores";

const pinia_simple = useSimple();
const pinia_counselling = useCounselling();
const router = useRouter();
const pinia_bulk_delete = useBulkDelete();
const {loading} = storeToRefs(pinia_counselling);
const auth = useAuth();

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

onMounted(async()=>{
    pinia_bulk_delete.$reset();
    await pinia_simple.fetchSimpleAllSessions()
    await pinia_simple.fetchSimpleCounsellors()
    // await pinia_counselling.indexStudent(null, null, null)
})

const perPage = ref(50);
const searchQuery = ref("");
const session_id = ref("999");
const section_id = ref("999");
const teacher_id = ref("999");

const debounceSearch = computed(()=> debounce(getResults,500));

const getResults = async (page = 1) => {
    if(session_id.value != "999"){
      await pinia_simple.fetchSimpleTheorySections(session_id.value)
    }
    if(session_id.value != "999" && section_id.value != "999"){
      await pinia_counselling.indexStudent(page, perPage.value, searchQuery.value, session_id.value, section_id.value, teacher_id.value);
      pinia_bulk_delete.selectAll = false
      // pinia_bulk_delete.selectedData = false
    }
}

// watch(
//   ()=>[session_id.value],
//   async() => {
//       pinia_simple.fetchSimpleTheorySections(session_id.value)
//   }
// )


// Multiple Select
const selectAllData = async () => {
    await pinia_bulk_delete.selectAllData(pinia_counselling.getStudents?.data);
}

const toggleSelectSelection = async (item) => {
    await pinia_bulk_delete.toggleSelection(item);
}

const assignToCounsellors = async () => {
    Swal.fire({
      title: "Are you sure?",
      text: "You would like to assign !",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Do it!"
    }).then(async(result) => {
      if (result.isConfirmed) {
        const resData = await pinia_counselling.assignStudentsToCounsellors(pinia_bulk_delete.getSelectedData, teacher_id.value);
        let inputs = document.querySelectorAll('.check_box');
        for (let i = 0; i < inputs.length; i++) {
            if(inputs[i].checked){
                inputs[i].checked = false;
            }
        }
        if(resData?.status){
            Swal.fire({
            title: "Successfully Assigned.!",
            text: "Students have been assigned to the counsellor.",
            icon: "success"
          });
          section_id.value = "999";
          session_id.value = "999";
          teacher_id.value = "999";
          searchQuery.value = "";
          pinia_bulk_delete.$reset();

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

</script>

<template>
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">Student's Data</h4>
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
                <div class="d-flex">
                    <div class="mr-auto">
                        <div class="tb_search">
                            <input type="text" @input="debounceSearch" v-model="searchQuery" placeholder="Search by Name, ID/Roll, Phone, Email" class="form-control" style="width: 300px;">
                        </div>
                    </div>        

                    <div class="num_rows">
                        <div class="form-group">
                            <select class="form-control" v-model="perPage" @change="getResults">
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="300">300</option>
                                <option value="400">400</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div> 
                </div>
                <div class="d-flex justify-content-first mb-2">
                  <div>
                      <select v-model="session_id" class="form-control" @change="getResults">
                          <option value="999">Select a Session</option> 
                          <option :value="session.id" v-for="session in pinia_simple.simple_all_sessions">{{ session.name }}</option>
                      </select>
                  </div>
                  <div class="ml-2">
                      <select v-model="section_id" class="form-control" @change="getResults" :disabled="session_id == '999'">
                          <option value="999">Select a Section</option> 
                          <option :value="section.id" v-for="section in pinia_simple.simple_theory_sections">{{ section.name }} | {{ section.shift }}</option>
                      </select>
                  </div>
                  <div class="ml-2">
                      <select v-model="teacher_id" class="form-control">
                          <option value="999">Select a Counsellor</option> 
                          <option :value="teacher.id" v-for="teacher in pinia_simple.simple_counsellors">{{ teacher.name }}</option>
                      </select>
                  </div>
                  <div v-if="pinia_bulk_delete.selectedData.length" class="pb-3 mx-2">
                      <button class="btn btn-sm btn-success py-2" :disabled="teacher_id == '999'" @click="assignToCounsellors">Assign ({{ pinia_bulk_delete.selectedData.length }} {{pinia_bulk_delete.selectedData.length == 1 ? 'student' : 'students'}})</button>
                  </div>
                </div>


                <Table v-if="pinia_counselling.getStudents?.data?.length > 0">
                    <template #tableHead>
                        <TableHead v-if="auth.can('student.delete')">
                            <input type="checkbox" class="check_box" v-model="pinia_bulk_delete.selectAll" @change="selectAllData">
                        </TableHead>
                        <TableHead>Name ({{ pinia_counselling.getStudents?.data?.length }})</TableHead>
                        <TableHead>Roll</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Section</TableHead>
                        <TableHead>Image</TableHead>
                        <TableHead>Status</TableHead>
                    </template>
                    <TableRow v-for="(user,index) in pinia_counselling.getStudents?.data" :key="index">
                        <TableData v-if="auth.can('student.delete')">
                            <input type="checkbox" class="check_box" :checked="pinia_bulk_delete.selectedData.some(std_id => std_id == user.id)" @change="toggleSelectSelection(user)">
                        </TableData>
                        <TableData>{{user.name}}</TableData>
                        <TableData><b>{{user.roll_no}}</b></TableData>
                        <TableData>{{user.phone}}</TableData>
                        <TableData class="text-info font-weight-bold">{{user.sections}}</TableData>
                        <TableData>
                            <img :src="$filters.makeImagePath(user.image)" alt="user Image" width="70">
                        </TableData>
                        <TableData v-if="user.is_verified==1" class="text-success"><b>Verified</b></TableData>
                        <TableData v-else class="text-danger"><b>Not Verified</b></TableData>
                    </TableRow>
                </Table>
                <div class="col-12 my-5" v-else> 
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>
                <Bootstrap5Pagination
                    :data="pinia_counselling.getStudents"
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
