<script setup>
import { onMounted,} from "vue";
import {storeToRefs} from "pinia"
import { useRouter} from "vue-router";
import { useCounselling, useBulkDelete, useAuth } from "@/stores";
const auth = useAuth();
const router = useRouter();
const pinia_counselling = useCounselling();
const pinia_bulk_delete = useBulkDelete();
const {loading} = storeToRefs(pinia_counselling);

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton} from "@/common/components/Form"

onMounted(async()=>{
    pinia_bulk_delete.$reset();
    await pinia_counselling.teacherCounselling(auth.user?.id);
})

const viewDetails = (item) => {
    router.push({name: 'counselling.view_details', params: {id: item?.id}});
}

</script>

<template>
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">Students Under Counselling</h4>
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
                <Table v-if="pinia_counselling.teacher_counselling.length > 0">
                    <template #tableHead>                        
                        <TableHead>Student ID</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Guardian's Number</TableHead>
                        <TableHead>Action</TableHead>
                    </template>
                    <TableRow v-for="(item,index) in pinia_counselling.teacher_counselling" :key="index">                       
                        <TableData>{{item.student.roll_no}}</TableData>                    
                        <TableData>{{item.student.name}}</TableData>                    
                        <TableData>{{item.student.phone}}</TableData>                    
                        <TableData class="text-center font-weight-bold">-</TableData>
                        <TableData>
                            <div class="d-flex" style="gap:4px">
                                <PrimaryButton type="button" @click.prevent="viewDetails(item)" title="View Details">
                                    <i class="fa fa-eye"></i>
                                </PrimaryButton>                                
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
