<script setup>
import { onMounted, ref, computed, watch } from "vue";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useReport, useTopic, useAuth, useSimple } from "@/stores";

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

const auth = useAuth();
const pinia_simple = useSimple();
const pinia_topic = useTopic();
const pinia_report = useReport();
const {assignment_reports, loading} = storeToRefs(pinia_report);
const {simple_topics} = storeToRefs(pinia_topic);

const topic_id = ref(null);
const section_id = ref('999');
const session_id = ref('999');

onMounted(async()=>{
    pinia_report.$reset();
    pinia_simple.fetchSimpleAllSections()
    pinia_simple.fetchSimpleAllSessions()
    pinia_topic.simpleTopics();
    if(topic_id.value != null){
      await pinia_report.indexAssignment(section_id.value, session_id.value, topic_id.value)
    }
})

const getResults = async () => {
    if(topic_id.value != null){
      await pinia_report.indexAssignment(section_id.value, session_id.value, topic_id.value)
    }
}

const debounceSearch = computed(()=> debounce(getResults,500));


const formattedTopics = computed(() =>
  simple_topics.value.map(t => `${t.id} - ${t.topic}`)
)

const labelToTopicId = (label) =>{

}

watch(topic_id, async (label) => {
  if (label) {
      await pinia_report.indexAssignment(section_id.value, session_id.value, topic_id.value)
  }
})


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
    <h3 class=" text-center text-success pt-3">Assignment's Report</h3>
    <div class="row">        
        <div class="col-12 mb-4">
            <div class="d-flex">
                <div class="mr-auto ">
                    <div class="tb_search d-flex">   
                        <span class="mr-2"></span>

                        <select v-model="section_id" id="" class="form-control" @input="debounceSearch" style="min-width: 70px;">
                            <option value="999">Select Section</option>                          
                            <option v-for="(section,index) in pinia_simple.simple_all_sections" :key="index" :value="section.id">{{section.name}} | {{section.shift}}</option> 
                        </select>                        
                        <span class="mr-2"></span>

                        <select v-model="session_id" id="" class="form-control" @input="debounceSearch" style="min-width: 130px;">
                            <option value="999">Select Session</option>
                            <option v-for="(session,index) in pinia_simple.simple_all_sessions" :key="index" :value="session.id">{{session.name}} | {{session.type}}</option> 
                        </select>
                        <span class="mr-2"></span>
                        
                    </div>
                </div>       
            </div>
        </div>  
        <div class="col-12 ml-2">
            <div class="d-flex">
               <v-select
                style="width: 50%"
                v-model="topic_id"
                :options="formattedTopics"
                placeholder="Select a topic"
              />
            <button class="btn btn-primary ml-3" @click.prevent="printTable">Print</button>
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
            <div class="card mx-2 mt-4" v-else>

              <div class="card-body" v-if="assignment_reports?.assignments?.length > 0">

                <h4 class="text-center text-success">Topic Info</h4>
                <Table  id="print-section">
                    <template #tableHead>
                        <TableHead>Topic Name</TableHead>
                        <TableHead>Point</TableHead>
                        <TableHead>Due Date</TableHead>
                    </template>
                    <TableRow>
                        <TableData style="white-space: nowrap;">{{assignment_reports?.topic?.topic}}</TableData>
                        <TableData><b>{{assignment_reports?.topic?.points}}</b></TableData>
                        <TableData><b>{{assignment_reports?.topic?.due_date}}</b></TableData>
  
                    </TableRow>
                </Table>
 
                <h4 class="text-center text-primary">Student Stats</h4>
                <Table  id="print-section">
                    <template #tableHead>
                        <TableHead>Student Name</TableHead>
                        <TableHead>Roll</TableHead>
                        <TableHead>Obtained Marks</TableHead>
                    </template>
                    <TableRow v-for="(assignment,index) in assignment_reports?.assignments" :key="index">
                        <TableData style="white-space: nowrap;">{{assignment.student.name}}</TableData>
                        <TableData><b>{{assignment.student.roll_no}}</b></TableData>
                        <TableData><b>{{assignment.marks}}</b></TableData>
   
                    </TableRow>
                </Table>
              </div>

              <div class="col-12" v-else> 
                <div class="card-body">
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>
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