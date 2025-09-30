import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useCounselling = defineStore("counselling", {
  state: () => ({
    students: [],
    counsellors: [],
    teacher_counselling: [],
    view_details: [],
    errors: {},
    loading: false,
  }),

  getters: {
    getStudents: (state)=>{
      return state.students;
    },
  },

  actions: {

    async teacherCounselling(teacher_id) {
        try {
            this.loading = true
            const res = await axiosInstance.get("/counselling/techer_counselling",{
                params:{
                    teacher_id
                }
            });
            if (res.status === 200) {
                this.teacher_counselling = res.data;
                return res.data;
            }
            } catch (error) {
            if (error.response.data) {
                throw error.response.data.errors;
            }
        }finally{
            this.loading = false
        }
    },

    async viewDetails(from,to,student_id) {
        try {
            this.loading = true
            const res = await axiosInstance.get("/counselling/view_details",{
                params:{
                    from,
                    to,
                    student_id
                }
            });
            if (res.status === 200) {
                this.view_details = res.data;
                return res.data;
            }
            } catch (error) {
            if (error.response.data) {
                throw error.response.data.errors;
            }
        }finally{
            this.loading = false
        }
    },

    async indexStudent(page, perPage, searchQuery, session_id, section_id) {
        try {
            if(searchQuery == null){
                this.loading = true
            }
            const res = await axiosInstance.get("/counselling/students",{
                params:{
                    page,
                    perPage,
                    search: searchQuery,
                    session_id,
                    section_id
                    }
            });
            if (res.status === 200) {
                this.students = res.data;
                return res.data;
            }
            } catch (error) {
            if (error.response.data) {
                throw error.response.data.errors;
            }
        }finally{
            this.loading = false
        }
    },

    async assignStudentsToCounsellors(student_ids, teacher_id) {
        try {
            
            this.loading = true
            const res = await axiosInstance.post("/counselling/students_assigned_to_counsellor",{
                    student_ids,
                    teacher_id
            });
            if (res.status === 200) {
                this.students = res.data;
                return res.data;
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
