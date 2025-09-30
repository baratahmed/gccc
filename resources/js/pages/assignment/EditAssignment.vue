<script setup>
import {reactive, onMounted} from "vue"
import {useRouter, useRoute} from "vue-router"
import {storeToRefs} from "pinia"
import { useAttendance,useNotification, useAuth } from "@/stores";
import Datepicker from 'vuejs3-datepicker';
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"

const auth = useAuth();
const router = useRouter();
const route = useRoute();
const pinia_attendance = useAttendance();
const notify = useNotification();
const {errors,loading,student_list} = storeToRefs(pinia_attendance);

const form = reactive({
    id: route.params?.id,
    group: 'SCIENCE',
    section: 'S1',
    shift: 'DAY',
    session: '2024-2025',
    date: new Date(),
    std_ids: [],
  
});

const extraFrom = reactive({
    scienceShow: true,
    bsShow: false,
    humShow: false,
});

function changeSections(){
  if(form.group == 'SCIENCE'){
    form.shift = null
    extraFrom.scienceShow = true
    extraFrom.bsShow = false
    extraFrom.humShow = false
  }else if(form.group == 'BUSINESS STUDIES'){
    form.shift = 'DAY'
    extraFrom.scienceShow = false
    extraFrom.bsShow = true
    extraFrom.humShow = false
    form.section = 'BS1'
  }else if(form.group == 'HUMANITIES'){
    form.shift = 'DAY'
    extraFrom.scienceShow = false
    extraFrom.bsShow = false
    extraFrom.humShow = true
    form.section = 'H1'
  }
}

onMounted(async()=>{
  pinia_attendance.loading = true
  const resData = await pinia_attendance.show(route.params?.id)
  form.id = resData.attendance.id
  form.group = resData.attendance.group
  form.section = resData.attendance.section
  form.shift = resData.attendance.shift
  form.session = resData.attendance.session
  form.date = resData.attendance.date
  form.std_ids = JSON.parse( resData.attendance.std_ids)
  pinia_attendance.student_list = resData.student_list
})


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
    notify.Error("Something went wrong!");
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
                <h3 class="card-title">Update Attendance</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="updateAttendance">
                <div class="card-body">

                  <div class="form-group">
                    <label for="user_group">Select Group</label>
                    <select v-model="form.group" @change="changeSections()" id="user_group" class="form-control">
                      <option value="SCIENCE">SCIENCE</option>
                      <option value="BUSINESS STUDIES">BUSINESS STUDIES</option>
                      <option value="HUMANITIES">HUMANITIES</option>
                    </select>
                    <span class="text-danger" v-if="errors?.group">{{ errors.group[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_section">Select Section</label>
                    <select v-model="form.section" id="user_section" class="form-control">
                      <option value="S1" v-show="extraFrom.scienceShow">S1</option>                     
                      <option value="S2" v-show="extraFrom.scienceShow">S2</option>                     
                      <option value="S3" v-show="extraFrom.scienceShow">S3</option>                     
                      <option value="BS1" v-show="extraFrom.bsShow">BS1</option>                     
                      <option value="BS2" v-show="extraFrom.bsShow">BS2</option>                     
                      <option value="BS3" v-show="extraFrom.bsShow">BS3</option>                     
                      <option value="H1" v-show="extraFrom.humShow">H1</option>                     
                      <option value="H2" v-show="extraFrom.humShow">H2</option>                     
                      <option value="H3" v-show="extraFrom.humShow">H3</option>                     
                    </select>
                    <span class="text-danger" v-if="errors?.section">{{ errors.section[0] }}</span>
                  </div>

                  <div class="form-group" v-show="extraFrom.bsShow || extraFrom.humShow">
                    <label for="user_shift">Select Shift</label>
                    <select v-model="form.shift" id="user_shift" class="form-control">
                      <option value="DAY">DAY</option>                     
                      <option value="EVENING">EVENING</option>                                        
                    </select>
                    <span class="text-danger" v-if="errors?.shift">{{ errors.shift[0] }}</span>
                  </div>                  

                  <div class="form-group">
                    <label for="user_session">Session</label>
                    <input type="text" class="form-control" v-model="form.session" id="user_session" placeholder="Enter Session">
                    <span class="text-danger" v-if="errors?.session">{{ errors.session[0] }}</span>
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