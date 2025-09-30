<script setup>
import {reactive, onMounted, ref, watch} from "vue"
import {useRouter, useRoute} from "vue-router"
import {storeToRefs} from "pinia"
import { useAttendance,useNotification, useAuth, useSimple} from "@/stores";
import Datepicker from 'vuejs3-datepicker';
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"

const pinia_simple = useSimple();
const auth = useAuth();
const router = useRouter();
const route = useRoute();
const pinia_attendance = useAttendance();
const notify = useNotification();
const {errors,loading,student_list} = storeToRefs(pinia_attendance);

const form = reactive({
    id: route.params?.id,
    group_id: '999',
    section_ids: null,
    session_id: '999',
    date: new Date(),
    std_ids: [],
  
});

onMounted(async()=>{
  pinia_attendance.loading = true
  pinia_simple.fetchSimpleAllSessions()

  if ((auth.user.role=='Admin') || (auth.user.role=='Teacher' && auth.user.group == 'GENERAL' && auth.user.subject_id != 11)) {
    pinia_simple.fetchSimpleAllGroups()
  } else {
    pinia_simple.fetchSimpleGroups(auth.user.group_id, auth.user.subject_id)
  }
  const resData = await pinia_attendance.show(route.params?.id)
  form.id = resData.attendance.id
  form.group_id = resData.attendance.group_id
  form.section_id = resData.attendance.section_id
  form.session_id = resData.attendance.session_id
  form.date = resData.attendance.date
  form.std_ids = JSON.parse( resData.attendance.std_ids)
  pinia_attendance.student_list = resData.student_list

  pinia_attendance.loading = false

})

watch(
  ()=>[form.group_id],
  async() => {
    await pinia_simple.fetchSimpleSections(form.group_id)
  }
)

async function fetchStudents(){
  await pinia_attendance.fetchStudents(form);
}

function toggleStudents(std_id){
    const index = form.std_ids.indexOf(std_id)
    if(index==-1){
        form.std_ids.push(std_id)
    }else{
        form.std_ids.splice(index,1)
    }
}

const updateAttendance = async() => {

  const resData = await pinia_attendance.update(form);

  if(resData?.status){
    notify.Success(resData.message);
    router.push({name: 'attendance.index'})
  }else{
    notify.Error(resData.message);
  }
}
</script>

<template>
    <section class="content pt-3">
      <div class="container-fluid">
        <div v-if="loading" class="text-center m-5">
            <span
                class="spinner-border spinner-border-sm mr-1 text-dark"
                style="padding: 12px; font-size: 20px;"
            ></span>
            <div>Loading....</div>
        </div>
        <div class="row" v-else>
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Attendance </h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="updateAttendance">
                <div class="card-body">

                  <div class="form-group" >
                      <label for="user_group">Select Group</label>
                      <select v-model="form.group_id" id="user_group" class="form-control">
                        <option value="999">Select a Group</option>
                        <option :value="group.id" v-for="group in pinia_simple.simple_all_groups" v-if="(auth.user.role=='Admin') || (auth.user.role=='Teacher' && auth.user.group == 'GENERAL' && auth.user.subject_id != 11)">{{ group.name }}</option>
                        <option :value="group.id" v-for="group in pinia_simple.simple_groups" v-else >{{ group.name }}</option>
                      </select>
                      <span class="text-danger" v-if="errors?.group_id">{{ errors.group_id[0] }}</span>
                  </div>

                  <div class="form-group" >
                    <label for="user_section">Select Section ( With Shift )</label>
                    <select v-model="form.section_id" id="user_section" class="form-control">
                      <option value="999">Select a Section</option> 
                      <option :value="section.id" v-for="section in pinia_simple.simple_sections">{{ section.name }} | {{section.shift}}</option>
                    </select>
                    <span class="text-danger" v-if="errors?.section_id">{{ errors.section_id[0] }}</span>
                  </div>                  

                  <div class="form-group" >
                    <label for="user_session">Session</label>
                    <select v-model="form.session_id" id="user_session" class="form-control">
                      <option value="999">Select a Session</option>
                      <option v-for="session in pinia_simple.simple_all_sessions" :key="session.id" :value="session.id">
                        {{ session.name }} ({{ session.type }})
                      </option>
                    </select>
                    <span class="text-danger" v-if="errors?.session_id">{{ errors.session_id[0] }}</span>
                  </div>

                  <div class="form-group pt-2">          
                      <datepicker 
                        v-model="form.date"
                        placeholder="Order Date"
                        :disabled-dates="{
                            from: new Date(),
                        }"
                        iconColor="red"
                        iconWidth="18"
                        iconHeight="18">
                      </datepicker>

                    <button type="button" @click.prevent="fetchStudents()" class="btn btn-primary float-right">Fetch Students</button>

                  </div>

                </div>

                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <h4 class="text-center m-3">Student List</h4>
                    </div>
                    <div class="col-12">
                      <div class="card">

                        <div class="card-body"> 
                         
                          <Table v-if="student_list?.length > 0">
                              <template #tableHead>                                 
                                  <TableHead>Name</TableHead>
                                  <TableHead>Roll</TableHead>
                                  <TableHead>Attendance</TableHead>                      
                              </template>
                              <TableRow v-for="(user,index) in student_list" :key="index">
                                  <TableData>{{user.name}}</TableData>
                                  <TableData>{{user.roll_no}}</TableData>
                                  <TableData>
                                    <div class="custom-control custom-switch">
                                      <input type="checkbox" class="custom-control-input" :checked="form.std_ids.indexOf(user.id) != -1" @change="toggleStudents(user.id)" :id="`student_${user.id}`">
                                      <label class="custom-control-label" :for="`student_${user.id}`">
                                          <span class="text-danger" v-if="form.std_ids.indexOf(user.id) == -1">Absent</span>
                                          <span class="text-success" v-else>Present</span>
                                      </label>
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


                <div class="form-group mx-3">
                  <button type="submit" class="btn btn-primary" :disabled="loading">Update</button>
                </div>


              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
</template>