import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";
import { useBulkDelete } from "@/stores";

export const useUser = defineStore("user", {
  state: () => ({
    students: [],
    teachers: [],
    admins: [],
    user: [],
    errors: {},
    loading: false,
  }),
  getters: {
    getStudents: (state)=>{
      return state.students;
    },
    getTeachers: (state)=>{
      return state.teachers;
    },
    getAdmins: (state)=>{
      return state.admins;
    },
  },

  actions: {
    async indexStudent(page, perPage, searchQuery) {
      try {
        if(searchQuery == null){
          this.loading = true
        }
        const res = await axiosInstance.get("/students",{
            params:{
              page,
              perPage,
              search: searchQuery
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
    async showStudent(id) {
      try {
        this.loading = true;
        const res = await axiosInstance.get(`/students/${id}`);
        this.user = res.data
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async storeStudent(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post('/students',form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async updateStudent(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post("/update/student",form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async destroyStudent(id) {
      try {
        const res = await axiosInstance.delete(`/students/${id}`);
        if (res.status === 200) {
            let index = this.students.data.findIndex(
              (item)=> item?.id == id
            );
            this.students.data.splice(index,1)
            return new Promise((resolve) => {
              resolve(res.data);
            });
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },
    async multipleDeleteStudent(selectedIds) {
      try {
        const res = await axiosInstance.delete('/student/multiple-delete',{
            params:{
              ids: selectedIds
            }
        });
        if (res.status === 200) {
            this.students.data = this.students.data.filter(
              (item)=> !selectedIds.includes(item?.id)
            );
            const pinia_bulk_delete = useBulkDelete();
            pinia_bulk_delete.$reset();
            return new Promise((resolve) => {
              resolve(res.data);
            });

        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },



  

    async indexTeacher(page, perPage, searchQuery) {
      try {
        if(searchQuery == null){
          this.loading = true
        }
        const res = await axiosInstance.get("/teachers",{
            params:{
              page,
              perPage,
              search: searchQuery
            }
        });
        if (res.status === 200) {
          this.teachers = res.data;
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
    async showTeacher(id) {
      try {
        this.loading = true;
        const res = await axiosInstance.get(`/teachers/${id}`);
        this.user = res.data
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async storeTeacher(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post('/teachers',form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async updateTeacher(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post("/update/teacher",form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },
    async destroyTeacher(id) {
      try {
        const res = await axiosInstance.delete(`/teachers/${id}`);
        if (res.status === 200) {
            let index = this.teachers.data.findIndex(
              (item)=> item?.id == id
            );
            this.teachers.data.splice(index,1)
            return new Promise((resolve) => {
              resolve(res.data);
            });
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },
    async multipleDeleteTeacher(selectedIds) {
      try {
        const res = await axiosInstance.delete('/teacher/multiple-delete',{
            params:{
              ids: selectedIds
            }
        });
        if (res.status === 200) {
            this.teachers.data = this.teachers.data.filter(
              (item)=> !selectedIds.includes(item?.id)
            );
            const pinia_bulk_delete = useBulkDelete();
            pinia_bulk_delete.$reset();
            return new Promise((resolve) => {
              resolve(res.data);
            });

        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },








    async indexAdmin(page, perPage, searchQuery) {
      try {
        if(searchQuery == null){
          this.loading = true
        }
        const res = await axiosInstance.get("/admins",{
            params:{
              page,
              perPage,
              search: searchQuery
            }
        });
        if (res.status === 200) {
          this.admins = res.data;
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

    async showAdmin(id) {
      try {
        this.loading = true;
        const res = await axiosInstance.get(`/admins/${id}`);
        this.user = res.data
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },

    async storeAdmin(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post('/admins',form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },

    async updateAdmin(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post("/update/admin",form);
        return new Promise((resolve) => {
          resolve(res.data);
        });
      } catch (error) {
        if (error.response.data) {
          this.errors = error.response.data.errors
        }
      }finally{
        this.loading = false;
      }
    },

    async destroyAdmin(id) {
      try {
        const res = await axiosInstance.delete(`/admins/${id}`);
        if (res.status === 200) {
            let index = this.admins.data.findIndex(
              (item)=> item?.id == id
            );
            this.admins.data.splice(index,1)
            return new Promise((resolve) => {
              resolve(res.data);
            });
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async multipleDeleteAdmin(selectedIds) {
      try {
        const res = await axiosInstance.delete('/admin/multiple-delete',{
            params:{
              ids: selectedIds
            }
        });
        if (res.status === 200) {
            this.admins.data = this.admins.data.filter(
              (item)=> !selectedIds.includes(item?.id)
            );
            const pinia_bulk_delete = useBulkDelete();
            pinia_bulk_delete.$reset();
            return new Promise((resolve) => {
              resolve(res.data);
            });

        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },
  },
});
