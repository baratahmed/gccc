<script setup>
import { onMounted, ref, computed} from "vue";
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useRouter } from "vue-router";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { useUser, useBulkDelete, useAuth } from "@/stores";

const auth = useAuth();
const pinia_user = useUser();
const router = useRouter();
const pinia_bulk_delete = useBulkDelete();
const {loading} = storeToRefs(pinia_user);

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

onMounted(async()=>{
    pinia_bulk_delete.$reset();
    await pinia_user.indexTeacher(null, null, null)
})

const perPage = ref(100);
const searchQuery = ref("");

const debounceSearch = computed(()=> debounce(getResults,500));

const getResults = async (page = 1) => {
    await pinia_user.indexTeacher(page, perPage.value, searchQuery.value)
}

// Multiple Delete
const selectAllData = async () => {
    await pinia_bulk_delete.selectAllData(pinia_user.getTeachers?.data);
}

const toggleSelectSelection = async (item) => {
    await pinia_bulk_delete.toggleSelection(item);
}

const multipleDeleteSubmit = async () => {
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
        const resData = await pinia_user.multipleDeleteTeacher(pinia_bulk_delete.getSelectedData);
        let inputs = document.querySelectorAll('.check_box');
        for (let i = 0; i < inputs.length; i++) {
            if(inputs[i].checked){
                inputs[i].checked = false;
            }
        }
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
        const resData = await pinia_user.destroyTeacher(id);
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

const goToTeacherEditPage = (user) => {
    router.push({name: 'teacher.edit', params: {id: user?.id}});
}

</script>

<template>
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">All Teachers</h4>
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
                            <input type="text" @input="debounceSearch" v-model="searchQuery" placeholder="Search by Name, Phone, Email" class="form-control" style="width: 300px;">
                        </div>
                    </div>        

                    <div class="num_rows">
                        <div class="form-group">
                            <select class="form-control" v-model="perPage" @change="getResults">
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
                                      
                  <div v-if="pinia_bulk_delete.selectedData.length" class="pb-3 mr-2">
                        <button class="btn btn-sm btn-danger py-2" @click="multipleDeleteSubmit">Delete ({{ pinia_bulk_delete.selectedData.length }} {{pinia_bulk_delete.selectedData.length == 1 ? 'item' : 'items'}})</button>
                    </div>

                    <div class="pb-3" v-if="auth.can('teacher.create')">
                        <router-link :to="{name: 'teacher.add'}" class="btn btn-sm btn-primary py-2">Add Teacher (+)</router-link>
                    </div>
                </div>


                <Table v-if="pinia_user.getTeachers?.data?.length > 0">
                    <template #tableHead>
                        <TableHead v-if="auth.can('teacher.delete')">
                            <input type="checkbox" class="check_box" v-model="pinia_bulk_delete.selectAll" @change="selectAllData">
                        </TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Image</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead v-if="auth.can('teacher.update') || auth.can('teacher.delete')">Action</TableHead>
                    </template>
                    <TableRow v-for="(user,index) in pinia_user.getTeachers?.data" :key="index">
                        <TableData v-if="auth.can('teacher.delete')">
                            <input type="checkbox" class="check_box" :checked="pinia_bulk_delete.selectAll" @change="toggleSelectSelection(user)">
                        </TableData>
                        <TableData>{{user.name}} <span v-if="user.is_counsellor == 1">(C)</span></TableData>
                        <TableData><b>{{user.phone}}</b></TableData>
                        <TableData>{{user.email}}</TableData>
                        <TableData>
                            <img :src="$filters.makeImagePath(user.image)" alt="user Image" width="70">
                        </TableData>
                        <TableData v-if="user.is_verified==1" class="text-success"><b>Verified</b></TableData>
                        <TableData v-else class="text-danger"><b>Not Verified</b></TableData>

                        <TableData v-if="auth.can('teacher.update') || auth.can('teacher.delete')">
                            <div class="d-flex" style="gap:4px">
                                <PrimaryButton type="button" @click.prevent="goToTeacherEditPage(user)" v-if="auth.can('teacher.update')">
                                    <i class="fa fa-edit"></i>
                                </PrimaryButton>
                                <DeleteButton type="button" @click="singleDelete(user?.id)" v-if="auth.can('teacher.delete')">
                                    <i class="fa fa-trash"></i>
                                </DeleteButton>
                            </div>
                        </TableData>
                    </TableRow>
                </Table>
                <div class="col-12" v-else> 
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>
                <Bootstrap5Pagination
                    :data="pinia_user.getTeachers"
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
