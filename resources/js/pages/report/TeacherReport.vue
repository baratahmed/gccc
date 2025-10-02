<script setup>
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
import XLSX from "xlsx-js-style";
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

const generatePdf = (subjects) => {
   const doc = new jsPDF();

  // Title
  doc.setFontSize(18);
  doc.setFont("helvetica", "bold");
  doc.text("Teachers Report", doc.internal.pageSize.getWidth() / 2, 20, {
    align: "center",
  });

  subjects.forEach((subj, index) => {
    // Add spacing between subjects
    let y = doc.lastAutoTable ? doc.lastAutoTable.finalY + 15 : 30;

    // Subject Name as a header
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.text(`Subject: ${subj.subject_name}`, doc.internal.pageSize.getWidth() / 2, y, {
      align: "center",
    });

    // Prepare table data
    const headers = [["Teacher Name", "Phone", "Total Classes", "Teacher ID"]];
    const rows = subj.teachers.map((t) => [
      t.name,
      t.phone,
      t.total_classes ?? "-",
      t.id,
    ]);

    // Draw table
    autoTable(doc, {
      head: headers,
      body: rows,
      startY: y + 5,
      theme: "grid", // "striped" | "plain" | "grid"
      headStyles: {
        fillColor: [41, 128, 185], // blue header
        textColor: [255, 255, 255],
        halign: "center",
        fontSize: 11,
        fontStyle: "bold",
      },
      bodyStyles: {
        fontSize: 10,
        halign: "center",
      },
      styles: {
        cellPadding: 4,
        lineWidth: 0.2,
      },
      margin: { left: 15, right: 15 },
    });
  });

  // Save file
  doc.save("Teachers-Report-"+new Date().toDateString()+".pdf");
};

function buildSheet(subjects, sheetTitle) {
  let rows = [];

  // Title row (merged across 4 cols)
  rows.push([
    {
      v: sheetTitle,
      s: {
        font: { bold: true, sz: 16 },
        alignment: { horizontal: "center", vertical: "center" },
      },
    },
    {}, {}, {}
  ]);

  rows.push([]); // blank row

  subjects.forEach((subj) => {
    // Subject name row (merged)
    rows.push([
      {
        v: `Subject: ${subj.subject_name}`,
        s: {
          font: { bold: true, sz: 14 },
          alignment: { horizontal: "center" },
        },
      },
      {}, {}, {}
    ]);

    // Header row
    rows.push([
      { v: "Teacher Name", s: { font: { bold: true } } },
      { v: "Phone", s: { font: { bold: true } } },
      { v: "Total Classes", s: { font: { bold: true } } },
      { v: "Teacher ID", s: { font: { bold: true } } },
    ]);

    // Teacher rows
    subj.teachers.forEach((t) => {
      rows.push([t.name, t.phone, t.total_classes ?? "-", t.id]);
    });

    rows.push([]);
  });

  const sheet = XLSX.utils.aoa_to_sheet(rows);

  // Merge title row
  sheet["!merges"] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 3 } }];

  // Merge each subject row
  rows.forEach((row, idx) => {
    if (row[0] && typeof row[0].v === "string" && row[0].v.startsWith("Subject:")) {
      sheet["!merges"].push({ s: { r: idx, c: 0 }, e: { r: idx, c: 3 } });
    }
  });

  return sheet;
}

const generateExcel = (data) => {
  
  const wb = XLSX.utils.book_new();
  const sheet = buildSheet(data, "Subjects & Teachers");
  XLSX.utils.book_append_sheet(wb, sheet, "Subjects");

  // Export
  XLSX.writeFile(wb, "Teachers-Report-"+new Date().toDateString()+".xlsx");

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

                    <button class="btn btn-primary mt-3 mr-2" :disabled="pinia_report.teacher_reports.length == 0" @click.prevent="generatePdf(pinia_report.teacher_reports)">Generate PDF</button>
                    <button class="btn btn-info mt-3" :disabled="pinia_report.teacher_reports.length == 0" @click.prevent="generateExcel(pinia_report.teacher_reports)">Generate Excel</button>

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