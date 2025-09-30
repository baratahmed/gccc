<script setup>
import { onMounted, ref, computed} from "vue";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useReport, useAuth, useSimple } from "@/stores";

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

const pinia_simple = useSimple();
const auth = useAuth();
const pinia_report = useReport();
const {teacher_reports, loading} = storeToRefs(pinia_report);

var d = new Date();
const from = ref( new Date(`${d.getFullYear()}-${d.getMonth()+1}-${1}`));
const to = ref(new Date());
const session_id = ref('999');

function formatLaravelDate(dateString) {
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const [year, month, day] = dateString.split("-");
  return `${parseInt(day)} ${months[parseInt(month) - 1]} ${year}`;
}

onMounted(async()=>{
    pinia_report.$reset();
    // pinia_simple.fetchSimpleAllSections()
    pinia_simple.fetchSimpleAllSessions()
    if(session_id.value != '999'){
      await pinia_report.indexTeacherReport(from.value, to.value, session_id.value)
    }
})

const getResults = async () => {
    if(session_id.value != '999'){
      await pinia_report.indexTeacherReport(from.value, to.value, session_id.value)
    }
}

const debounceSearch = computed(()=> debounce(getResults,500));


function printTable() {
  const printContent = document.getElementById('print-section').innerHTML;
  const originalContent = document.body.innerHTML;

  document.body.innerHTML = printContent;
  window.print();

  document.body.innerHTML = originalContent;
  location.reload();

}
</script>
<template lang=""> 
    <h3 class=" text-center text-success pt-3">Teacher's Report </h3>
    <div class="row">        
        <div class="col-12 mb-4">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <div class="tb_search d-flex">
                        <datepicker
                            v-model="from"
                            @input="debounceSearch"
                            placeholder="From"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                        <span class="mr-2"></span>
                        <datepicker
                            v-model="to"
                            @input="debounceSearch"
                            placeho lder="To"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                        <span class="mr-2"></span>
                        <select v-model="session_id" id="" class="form-control" @input="debounceSearch" style="min-width: 105px;">
                            <option value="999">Select Session</option>
                            <option v-for="(session,index) in pinia_simple.simple_all_sessions" :key="index" :value="session.id">{{session.name}} | {{session.type}}</option> 
                        </select>
                    </div>

                    <button class="btn btn-primary mt-3" @click.prevent="printTable">Print</button>

                </div>       
            </div>
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
              <div class="card-body" v-if="teacher_reports.length > 0">
                <div>
                    <div v-for="(item,index) in teacher_reports" :key="index" >
                      <h4 class="text-success text-center my-3">{{ item.subject_name }}</h4>

                      <Table v-if="item.teachers.length > 0">
                          <template #tableHead>          
                              <TableHead class='bg-dark'>Name</TableHead>
                              <TableHead class='bg-dark'>Phone</TableHead>
                              <TableHead class='bg-dark'>Total Classes</TableHead>    
                          </template>
                          <TableRow v-for="(data,k,i) in item.teachers" :key="i">
                              <TableData class="text-secondary"><b>{{data.name}}</b></TableData>
                              <TableData class="text-secondary"><b>{{data.phone}}</b></TableData>
                              <TableData class="text-secondary"><b>{{data.total_classes ?? 0}}</b></TableData>
                          </TableRow>
                      </Table>
                      <div v-else>
                          <h5 class="text-danger text-center mb-5 mt-2">No classes have been taken!</h5>
                      </div>
                    </div>
                </div>    

                <!-- <Table  id="print-section">
                    <template #tableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Phone</TableHead>
                        <TableHead>Total Classes</TableHead>                     
                        <TableHead>Dates</TableHead>                     
                    </template>
                    <TableRow v-for="(user,index) in teacher_reports" :key="index">
                        <TableData style="white-space: nowrap;">{{user.name}}</TableData>
                        <TableData><b>{{user.phone}}</b></TableData>
                        <TableData><b>{{user.attendances.length}}</b></TableData>
                        <TableData><b>{{user.attendances.map(item => formatLaravelDate(item.date)).join(', ')}}</b></TableData>
   
                    </TableRow>
                </Table> -->



              </div>

              <div class="col-12" v-else-if="session_id=='999'"> 
                <div class="card-body">
                  <h5 class="text-center text-danger" >Select a session</h5>
                </div>
              </div>
              <div class="col-12" v-else  v-show="loading"> 
                <h5 class="text-center text-danger" >No Data Found</h5>
              </div>       

              
            </div>
            <!-- /.card -->
        </div>


    </div>
</template>

<style scoped>
.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.vertical-text {
  writing-mode: vertical-lr;
  text-orientation: mixed;
  padding: 5px;
  white-space: nowrap;
  transform: rotate(180deg);
}

@media print {
  @page {
    size: A4 landscape;
    margin: 10mm;
  }

  button {
    display: none !important;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }

  th, td {
    padding: 4px 8px;
    border: 1px solid #000;
    text-align: center;
  }
}

</style>