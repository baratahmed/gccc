<script setup>
import {reactive, onMounted, ref, watch, computed} from "vue"
import {useRouter} from "vue-router"
import {storeToRefs} from "pinia"
import { useAttendance,useNotification, useAuth, useSimple } from "@/stores";
import Datepicker from 'vuejs3-datepicker';
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"

const pinia_simple = useSimple();
const router = useRouter();
const auth = useAuth();
const pinia_attendance = useAttendance();
const notify = useNotification();
const {errors,loading, student_list} = storeToRefs(pinia_attendance);
const {simple_sections} = storeToRefs(pinia_simple);

onMounted(()=>{
    pinia_simple.fetchSimpleAllSessions()
    if ((auth.user.role=='Admin') || (auth.user.role=='Teacher' && auth.user.group == 'GENERAL' && auth.user.subject_id != 11)) {
      pinia_simple.fetchSimpleAllGroups()
    } else {
      pinia_simple.fetchSimpleGroups(auth.user.group_id, auth.user.subject_id)
    }
    pinia_attendance.student_list = [];
})
const checkBox = ref();
const form = reactive({
    subject_id: auth.user?.subject_id,
    session_id: '999',
    group_id: '999',
    type: 'THEORY',
    section_ids: null,
    date: new Date(),
    std_ids: [],
});


watch(
  ()=>[form.group_id],
  async() => {
    await pinia_simple.fetchSimpleSections(form)
    form.section_ids = null
  }
)

watch(
  ()=>[form.type],
  async() => {
    await pinia_simple.fetchSimpleSections(form)
    form.section_ids = null
  }
)


const formattedSections = computed(() =>
  simple_sections.value.map(t => ({
    id: t.id,
    label: `${t.name}-${t.shift}`
  }))
)

async function fetchStudents(){
  form.std_ids = [];
  const resData = await pinia_attendance.fetchStudents(form);
  if(resData?.message){
    notify.Error(resData.message);
  }
}

function toggleAllStudents(){
  if(checkBox.value){
    student_list.value.forEach(student => {
      form.std_ids.push(student.id)
    })
  }else{
    form.std_ids = []
  }
}

function toggleStudents(std_id){
    const index = form.std_ids.indexOf(std_id)
    if(index==-1){
        form.std_ids.push(std_id)
    }else{
        form.std_ids.splice(index,1)
    }
}

const storeAttendance = async() => {
  
  const resData = await pinia_attendance.store(form);

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
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Attendance</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="storeAttendance">
                <div class="card-body">
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

                  <div class="form-group" >
                    <label for="user_group">Select Group</label>
                    <select v-model="form.group_id" id="user_group" class="form-control" :disabled="form.session_id == '999'">
                      <option value="999">Select a Group</option>
                      <option :value="group.id" v-for="group in pinia_simple.simple_all_groups" v-if="(auth.user.role=='Admin') || (auth.user.role=='Teacher' && auth.user.group == 'GENERAL' && auth.user.subject_id != 11)">{{ group.name }}</option>
                      <option :value="group.id" v-for="group in pinia_simple.simple_groups" v-else >{{ group.name }}</option>
                    </select>
                    <span class="text-danger" v-if="errors?.group_id">{{ errors.group_id[0] }}</span>
                  </div>

                  <div class="form-group">
                      <label>Select Type </label>
                      <div class="d-flex">
                          <div class="form-check">
                            <input class="form-check-input" id="theory" type="radio" v-model="form.type" value="THEORY">
                            <label class="form-check-label" for="theory">THEORY</label>
                          </div>
                          <div class="form-check ml-4">
                            <input class="form-check-input" id="lab" type="radio" v-model="form.type" value="LAB" :disabled="(form.group_id == 1 && (auth.user?.subject_id == 1 || auth.user?.subject_id == 2)) || (form.group_id != 1 && auth.user?.subject_id != 3)">
                            <label class="form-check-label" for="lab">LAB</label>
                          </div>
                      </div>                      
                    </div>

                  <div class="form-group" >
                    <label for="user_section">Select Section ( With Shift )</label>
                    <v-select
                       v-model="form.section_ids"
                      :options="formattedSections"
                       label="label"
                      :multiple="true"
                      :reduce="option => option.id"
                      placeholder="Select Sections"
                    />
                    <span class="text-danger" v-if="errors?.section_ids">{{ errors.section_ids[0] }}</span>
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
                    
                    <!-- <button type="button" @click.prevent="fetchStudents()" class="btn btn-primary ml-2">SA</button>
                    <button type="button" @click.prevent="fetchStudents()" class="btn btn-primary ml-2">SB</button> -->
                    <button type="button" @click.prevent="fetchStudents()" class="btn btn-primary float-right">Fetch Students</button>

                  </div>

                </div>

                <div class="container-fluid" v-if="student_list.length > 0">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="text-center m-3 border p-2">Student List ({{ student_list.length }}) &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" v-model="checkBox" @change="toggleAllStudents()" id="checkAll"> <label style="font-size: 15px;" for="checkAll">Check All</label></h5>
                    </div>
                    <div class="col-12">
                        <div class="px-2">
                         
                          <Table v-if="student_list.length > 0">
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
                                      <input type="checkbox" class="custom-control-input" @change="toggleStudents(user.id)" :checked="form.std_ids.includes(user.id)" :id="`student_${user.id}`">
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
                      <!-- /.card -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>



                <div class="form-group mx-3" v-if="student_list.length > 0">
                  <button type="submit" class="btn btn-primary" :disabled="loading">Submit</button>
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