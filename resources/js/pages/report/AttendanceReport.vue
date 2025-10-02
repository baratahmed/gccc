<script setup>
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
import XLSX from "xlsx-js-style";
import axiosInstance from "@/services/AxiosService";
import { onMounted, ref, computed, watch, reactive} from "vue";
import {storeToRefs} from "pinia";
import { debounce } from "lodash";
import { useReport, useAuth, useSimple } from "@/stores";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

const pinia_simple = useSimple();
const auth = useAuth();
const pinia_report = useReport();
const {attendance_reports, loading} = storeToRefs(pinia_report);

var d = new Date();
const dateRange = ref([])

const form = reactive({
    from: ref( new Date(`${d.getFullYear()}-${d.getMonth()+1}-${1}`)),
    to: ref(new Date()),
    session_id: '999',
    group_id: '999',
    shift: 'DAY',
    subject_id: '999',
});

function getDatesInRange(from, to) {
  const dates = []
  let currentDate = new Date(from)
  while (currentDate <= to) {
    dates.push(currentDate.toLocaleDateString('en-CA', {timeZone: 'Asia/Dhaka'}))
    currentDate.setDate(currentDate.getDate() + 1)
  }
  return dates
}

dateRange.value = getDatesInRange(form.from, form.to)

function formatLaravelDate(dateString) {
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  const [year, month, day] = dateString.split("-");
  return `${parseInt(day)} ${months[parseInt(month) - 1]}`;
}

onMounted(async()=>{
    pinia_report.$reset();
    pinia_simple.fetchSimpleAllSessions()
    // if(section_ids.value != null && session_id.value != '999'){
    //   await pinia_report.indexAttendance(from.value, to.value, section_ids.value, session_id.value)
    // }
})

const getResults = async () => {
    if(form.session_id != '999' && form.group_id != '999' && form.subject_id != '999'){
      await pinia_report.indexAttendance(form)
    }
    dateRange.value = getDatesInRange(form.from, form.to)
}

watch(
  ()=>[form.group_id],
  async() => {
      pinia_simple.fetchSimpleSubjects(form.group_id)
  }
)

const debounceSearch = computed(()=> debounce(getResults,500));

const getTotalClasses = (student) => {
    var count = 0;
    pinia_report.attendance_reports?.attendances.forEach(attendance => {
        attendance.sections.forEach(section => {
            if(student.sections.some(sec => sec.section_id === section.section_id)){
                if(attendance.type == 'THEORY'){
                    count++
                }else{
                    count += 2
                }
            }
        })
    })
    return count;
}

const getTotalPresent = (student_id) => {
    var count = 0;
    pinia_report.attendance_reports?.attendances.forEach(attendance => {
        const ids = JSON.parse(attendance.std_ids);
        if(ids.includes(Number(student_id))){
            if(attendance.type == 'THEORY'){
                count++
            }else{
                count += 2
            }
        }
    })
    return count;
}

const presentAbsentStatus = (student, date) => {
  const attendances = pinia_report.attendance_reports?.attendances.filter(a => a.date === date);

  if (attendances.length == 0) return '-';

  var count = 0;
  attendances.forEach(attendance => {
      const ids = JSON.parse(attendance.std_ids);
      if(ids.includes(Number(student.id))){
          if(attendance.type == 'THEORY'){
              count++
          }else{
              count += 2
          }
      }
  })
  return count;
};

const percentage = (student) => {
  const total_classes = getTotalClasses(student);
  if (total_classes == 0) return 0.0;
  return ((getTotalPresent(student.id) / total_classes) * 100).toFixed(1);
};

const generatePdf = (students, attendances) => {
  const doc = new jsPDF("landscape");

  // Title
  doc.setFontSize(16);
  doc.setFont("helvetica", "bold");
  doc.text("Attendance Report", doc.internal.pageSize.getWidth() / 2, 15, { align: "center" });

  // --- Extract dates from attendance records ---
  const attendanceDates = attendances.map(a => a.date);
  
  // Dynamic headers
  const head = [
    ["Name", "Roll", ...attendanceDates, "Total Present", "Total Classes", "Percentage"]
  ];

  // --- Build table rows ---
  const body = students.map(student => {
    let totalPresent = 0;
    let totalClasses = attendanceDates.length;

    // Check attendance for each date
    const dateMarks = attendanceDates.map(date => {
      const attendance = attendances.find(a => a.date === date);
      if (attendance) {
        const presentIds = JSON.parse(attendance.std_ids);
        if (presentIds.includes(student.id)) {
          totalPresent++;
          return "1"; // Present
        } else {
          return "-"; // Absent
        }
      }
      return "-";
    });

    // Percentage
    const percentage = totalClasses > 0 ? ((totalPresent / totalClasses) * 100).toFixed(1) + "%" : "0%";

    return [
      student.name,
      student.roll_no,
      ...dateMarks,
      totalPresent,
      totalClasses,
      percentage
    ];
  });

  // --- Create fancy table ---
  autoTable(doc, {
    head: head,
    body: body,
    startY: 25,
    theme: "grid",
    headStyles: {
      fillColor: [240, 240, 240],
      textColor: [0, 0, 0],
      fontSize: 10,
      halign: "center",
      valign: "middle",
      fontStyle: "bold"
    },
    bodyStyles: {
      fontSize: 9,
      halign: "center",
      valign: "middle"
    },
    columnStyles: {
      0: { halign: "left", fontStyle: "bold" }, // Name
      1: { halign: "center", fontStyle: "bold" }, // Roll
      [head[0].length - 3]: { textColor: [0, 128, 0] }, // Total Present green
      [head[0].length - 2]: { textColor: [0, 128, 0] }, // Total Classes green
      [head[0].length - 1]: { textColor: [0, 0, 255], fontStyle: "bold" } // Percentage blue
    },
    styles: { cellPadding: 3 }
  });
  doc.save("Attendance-Report-"+new Date().toDateString()+".pdf");
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
    <h3 class=" text-center text-success pt-3">Attendance Report</h3>

    <div class="row">        
        <div class="col-12 mb-4">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <div class="tb_search d-flex">
                        <datepicker
                            v-model="form.from"
                            @input="debounceSearch"
                            placeholder="From"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                        <span class="mr-2"></span>
                        <datepicker 
                            v-model="form.to"
                            @input="debounceSearch"
                            placeho lder="To"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                        <span class="mr-2"></span>                     
                         
                        <select v-model="form.session_id" id="" class="form-control" @input="debounceSearch" style="max-width: 200px;">
                            <option value="999">Select Session</option>
                            <option v-for="(session,index) in pinia_simple.simple_all_sessions" :key="index" :value="session.id">{{session.name}} | {{session.type}}</option> 
                        </select>
                        <span class="mr-2"></span>
                        <select v-model="form.group_id" id="" class="form-control" @input="debounceSearch" style="max-width: 200px;">
                            <option value="999">Select Group</option>
                            <option value="1">SCIENCE</option> 
                            <option value="2">BUSINESS STUDIES</option> 
                            <option value="3">HUMANITIES</option> 
                        </select>
                        <span class="mr-2"></span>
                         <select v-model="form.shift" id="" class="form-control" @input="debounceSearch" style="max-width: 105px;">
                            <option value="DAY">DAY</option> 
                            <option value="EVENING">EVENING</option> 
                        </select>
                        <span class="mr-2"></span>
                        <select v-model="form.subject_id" id="" class="form-control" @input="debounceSearch" style="max-width: 200px;" :disabled="form.group_id == '999'">
                            <option value="999">Select Subject</option>
                            <option v-for="(subject,index) in pinia_simple.simple_subjects" :key="index" :value="subject.id">{{subject.name}}</option> 
                        </select>
                    </div>

                    <button class="btn btn-primary mt-3 mr-2" :disabled="attendance_reports.length == 0" @click.prevent="generatePdf(attendance_reports.students, attendance_reports.attendances)">Generate PDF</button>
                    <button class="btn btn-info mt-3" :disabled="attendance_reports.length == 0" @click.prevent="generateExcel(attendance_reports.length)">Generate Excel</button>
                    
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
              <div class="card-body" v-if="attendance_reports?.students?.length > 0">
                <Table id="print-section">
                    <template #tableHead>                        
                        <TableHead>Name</TableHead>
                        <TableHead>Roll</TableHead>
                        <TableHead v-for="date in dateRange" :key="date" class="vertical-text">{{ formatLaravelDate(date) }}</TableHead>
                        <TableHead class="vertical-text text-success">Total Present</TableHead>
                        <TableHead class="vertical-text text-success">Total Classes</TableHead>
                        <TableHead class="vertical-text text-primary">Percentage</TableHead>
                    </template>
                    <TableRow v-for="(user,index) in attendance_reports?.students" :key="index">
                        <TableData style="white-space: nowrap;">{{user.name}}</TableData>
                        <TableData><b>{{user.roll_no}}</b></TableData>
                        <TableData style="white-space: nowrap;" v-for="date in dateRange" :key="date">
                            <span><b>{{presentAbsentStatus(user, date)}}</b></span>
                        </TableData>
                        <TableData class="text-success"><b>{{getTotalPresent(user.id)}}</b></TableData>
                        <TableData class="text-success"><b>{{getTotalClasses(user)}}</b></TableData>
                        <TableData class="text-primary"><b>{{percentage(user)}}%</b></TableData>
                    </TableRow>
                </Table>
              </div>
              <!-- <div class="col-12" v-else-if="section_ids==null"> 
                <div class="card-body">
                  <h5 class="text-center text-danger">Select a Section</h5>
                </div>
              </div> -->
              <div class="col-12" v-else-if="form.session_id=='999'"> 
                <div class="card-body">
                  <h5 class="text-center text-danger">Select a Session</h5>
                </div>
              </div>
              <div class="col-12" v-else-if="form.group_id=='999'"> 
                <div class="card-body">
                  <h5 class="text-center text-danger">Select a Group</h5>
                </div>
              </div>
              <div class="col-12" v-else-if="form.subject_id=='999'"> 
                <div class="card-body">
                  <h5 class="text-center text-danger">Select a Subject</h5>
                </div>
              </div>
              <div class="col-12" v-else v-show="loading"> 
                <h5 class="text-center text-danger" >No Data Found</h5>
              </div>             
            </div>
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