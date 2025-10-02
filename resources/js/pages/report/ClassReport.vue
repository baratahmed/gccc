<script setup>
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
import XLSX from "xlsx-js-style";

import { onMounted,ref, computed} from "vue";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useReport,useSimple } from "@/stores";
const pinia_report = useReport();
const {loading} = storeToRefs(pinia_report); 
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

const pinia_simple = useSimple();

const today = ref(new Date());
const session_id = ref('999');
const shift = ref('DAY');

onMounted(async()=>{
    pinia_simple.fetchSimpleAllSessions();
    // await pinia_report.indexClassReport();
})

const getResults = async () => {
    if(session_id.value != '999'){
      await pinia_report.indexClassReport(today.value, session_id.value, shift.value)
    }
}

const debounceSearch = computed(()=> debounce(getResults,500));


const generatePdf = (data) => {
  const doc = new jsPDF();
  let currentY = 20; // Track vertical position

  // ---- Helper: Add Section Header ----
  const addHeader = (text, yOffset = 10, size = 16, color = [0, 0, 0]) => {
    doc.setFontSize(size);
    doc.setTextColor(...color);
    doc.setFont("helvetica", "bold");
    doc.text(text, 14, currentY + yOffset);
    currentY += yOffset + 4; // Move down
  };

  // ---- Title: Theory ----
  addHeader("Theory Classes", 0, 18, [33, 37, 41]);

  data.theory.forEach((section) => {
    addHeader(`Section: ${section.section_name}`, 8, 13, [0, 102, 204]);

    autoTable(doc, {
      startY: currentY + 2,
      head: [["ID", "Teacher", "Subject", "Present", "Submitted At"]],
      body: section.attendances.map((att) => [
        att.id,
        att.teacher,
        att.subject,
        att.present,
        att.submitted_at,
      ]),
      theme: "grid",
      styles: { fontSize: 10 },
      headStyles: { fillColor: [0, 123, 255], textColor: 255, fontStyle: "bold" },
      didDrawPage: (data) => {
        currentY = data.cursor.y; // update tracker
      },
    });
    currentY = doc.lastAutoTable.finalY + 6;
  });

  // ---- Title: Lab ----
  addHeader("Lab Classes", 14, 18, [33, 37, 41]);

  data.lab.forEach((section) => {
    addHeader(`Section: ${section.section_name}`, 8, 13, [220, 53, 69]);

    autoTable(doc, {
      startY: currentY + 2,
      head: [["ID", "Teacher", "Subject", "Present", "Submitted At"]],
      body: section.attendances.map((att) => [
        att.id,
        att.teacher,
        att.subject,
        att.present,
        att.submitted_at,
      ]),
      theme: "grid",
      styles: { fontSize: 10 },
      headStyles: { fillColor: [220, 53, 69], textColor: 255, fontStyle: "bold" },
      didDrawPage: (data) => {
        currentY = data.cursor.y; // update tracker
      },
    });
    currentY = doc.lastAutoTable.finalY + 6;
  });

  // Save file
  doc.save("Class-Report-"+new Date().toDateString()+".pdf");
};


function buildSheet(data, sheetTitle) {
  let rows = [];

  // Title row
  rows.push([
    {
      v: sheetTitle,
      s: {
        font: { bold: true, sz: 16 },
        alignment: { horizontal: "center" },
      },
    },
  ]);

  // Blank row
  rows.push([]);

  data.forEach((section) => {
    // Section name row
    rows.push([
      {
        v: `Section: ${section.section_name}`,
        s: {
          font: { bold: true, sz: 14 },
          alignment: { horizontal: "center" },
        },
      },
      {}, {}, {} 
    ]);

    // Header row
    rows.push([
      { v: "Teacher", s: { font: { bold: true } } },
      { v: "Subject", s: { font: { bold: true } } },
      { v: "Present", s: { font: { bold: true } } },
      { v: "Submitted At", s: { font: { bold: true } } },
    ]);

    // Attendance rows
    section.attendances.forEach((att) => {
      rows.push([att.teacher, att.subject, att.present, att.submitted_at]);
    });

    rows.push([]); // gap between sections
  });

   const sheet = XLSX.utils.aoa_to_sheet(rows);

  // Merge first row across 4 columns
  sheet["!merges"] = [
    { s: { r: 0, c: 0 }, e: { r: 0, c: 3 } }, // Title merge
  ];

  // Merge each section header row too (optional)
  rows.forEach((row, idx) => {
    if (
      row[0] &&
      typeof row[0].v === "string" &&
      row[0].v.startsWith("Section:")
    ) {
      sheet["!merges"].push({ s: { r: idx, c: 0 }, e: { r: idx, c: 3 } });
    }
  });

  return sheet;
}

const generateExcel = (response) => {

  const wb = XLSX.utils.book_new();
  const theorySheet = buildSheet(response.theory, "Theory Classes");
  const labSheet = buildSheet(response.lab, "Lab Classes");

  XLSX.utils.book_append_sheet(wb, theorySheet, "Theory");
  XLSX.utils.book_append_sheet(wb, labSheet, "Lab");

  // Export
  XLSX.writeFile(wb, "Class-Report-"+new Date().toDateString()+".xlsx");

}

</script>

<template>
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 mb-4">
              <div class="d-flex">
                  <div class="mr-auto p-2">
                      <div class="tb_search d-flex">
                          <datepicker
                              v-model="today"
                              @input="debounceSearch"
                              placeholder="From"
                              iconColor="red"
                              iconWidth="18"
                              iconHeight="18">
                          </datepicker>
                          <span class="mr-2"></span>

                          <select v-model="session_id" class="form-control" @input="debounceSearch" style="min-width: 200px;">
                              <option value="999">Select Session</option>
                              <option v-for="(session,index) in pinia_simple.simple_all_sessions" :key="index" :value="session.id">{{session.name}} | {{session.type}}</option> 
                          </select>

                          <span class="mr-2"></span>

                          <select v-model="shift" class="form-control" @input="debounceSearch" style="min-width: 105px;">
                              <option value="DAY">DAY</option>
                              <option value="EVENING">EVENING</option>
                          </select>
                      </div>

                      <button class="btn btn-primary mt-3 mr-2" :disabled="pinia_report.class_reports.length == 0" @click.prevent="generatePdf(pinia_report.class_reports)">Generate PDF</button>
                      <button class="btn btn-info mt-3" :disabled="pinia_report.class_reports.length == 0" @click.prevent="generateExcel(pinia_report.class_reports)">Generate Excel</button>

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
              <div class="card-body">
                <div v-if="pinia_report.class_reports?.theory?.length > 0">
                    <h3 class="text-center"><span class="border border-dark px-2">Theory Classes</span></h3>
                    <div v-for="(attendances,index) in pinia_report.class_reports.theory" :key="index" >
                    <h4 class="text-success text-center mt-3">Section: {{ attendances.section_name }}</h4>

                    <Table v-if="attendances.attendances.length > 0">
                        <template #tableHead>          
                            <TableHead class="bg-success">Subject</TableHead>                  
                            <TableHead class="bg-success">Teacher Name</TableHead>
                            <TableHead class="bg-success">Present</TableHead>
                            <TableHead class="bg-success">Submitted At</TableHead>
                        </template>
                        <TableRow v-for="(data,k,i) in attendances.attendances" :key="i">
                            <TableData class="text-secondary"><b>{{data.subject}}</b></TableData>
                            <TableData class="text-secondary"><b>{{data.teacher}}</b></TableData>
                            <TableData class="text-secondary"><b><span class="text-primary">{{data.present}}</span></b></TableData>   
                            <TableData class="text-secondary"><b>{{data.submitted_at}}</b></TableData> 
                        </TableRow>
                    </Table>
                    <div v-else>
                        <h5 class="text-danger text-center mb-5 mt-2">No classes have been taken!</h5>
                    </div>
                    </div>              
                </div>

                <div v-if="pinia_report.class_reports?.lab?.length > 0" class="mt-5">
                    <h3 class="text-center"><span class="border border-dark px-2">Lab Classes</span></h3>
                    <div v-for="(attendances,index) in pinia_report.class_reports.lab" :key="index" >
                    <h4 class="text-success text-center mt-3">Section: {{ attendances.section_name }}</h4>

                    <Table v-if="attendances.attendances.length > 0">
                        <template #tableHead>          
                            <TableHead class="bg-dark">Subject</TableHead>                  
                            <TableHead class="bg-dark">Teacher Name</TableHead>
                            <TableHead class="bg-dark">Present</TableHead>
                            <TableHead class="bg-dark">Submitted At</TableHead>
                        </template>
                        <TableRow v-for="(data,k,i) in attendances.attendances" :key="i">
                            <TableData class="text-secondary"><b>{{data.subject}}</b></TableData>
                            <TableData class="text-secondary"><b>{{data.teacher}}</b></TableData>
                            <TableData class="text-secondary"><b><span class="text-primary">{{data.present}}</span></b></TableData>   
                            <TableData class="text-secondary"><b>{{data.submitted_at}}</b></TableData> 
                        </TableRow>
                    </Table>
                    <div v-else>
                        <h5 class="text-danger text-center mb-5 mt-2">No classes have been taken!</h5>
                    </div>
                    </div>
                </div>

                <div class="col-12" v-else> 
                    <div class="card-body">
                    <h5 class="text-center text-danger" >No Data Found</h5>
                    </div>
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
