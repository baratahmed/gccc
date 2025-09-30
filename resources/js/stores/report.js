import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useReport = defineStore("report", {
  state: () => ({
    class_reports: [],
    attendance_reports: [],
    teacher_reports: [],
    assignment_reports: [],
    errors: {},
    loading: false,
  }),

  actions: {
    async indexClassReport(today, session_id, shift) {
      try {
        this.loading = true
        const res = await axiosInstance.get("/class/report",{
          params:{
            today, session_id, shift
          }
        });
        if (res.status === 200) {
          this.class_reports =  res.data;
          return 'Success';
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.loading = false
      }
    },


    async indexAttendance(form) {
      try {
        this.loading = true
        const res = await axiosInstance.get("/attendance/report",{
            params:{
              from: form.from,
              to: form.to,
              session_id: form.session_id,
              group_id: form.group_id,
              shift: form.shift,
              subject_id: form.subject_id,
            }
        });
        if (res.status === 200) {
          this.attendance_reports =  res.data;
          return 'Success';
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.loading = false
      }
    },


    async indexTeacherReport(from, to, session_id) {
      try {
        this.loading = true
        const res = await axiosInstance.get("/teacher/report",{
            params:{
              from,
              to,
              session_id
            }
        });
        if (res.status === 200) {
          this.teacher_reports =  res.data;
          return 'Success';
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.loading = false
      }
    },


     async indexAssignment(section, shift, topic_id) {
      try {
        this.loading = true
        const res = await axiosInstance.get("/assignment/report",{
            params:{
              section,
              shift,
              topic_id
            }
        });
        if (res.status === 200) {
          this.assignment_reports =  res.data;
          return 'Success';
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.loading = false
      }
    },


  },
});
