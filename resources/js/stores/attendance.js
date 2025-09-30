import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";
import { useBulkDelete } from "@/stores";

export const useAttendance = defineStore("attendance", {
  state: () => ({
    items: [],
    student_list: [],
    attendance: [],
    // roles: [],
    errors: {},
    loading: false,
  }),
  getters: {
    getItems: (state)=>{
      return state.items;
    },
  },

  actions: {
    async index(page, perPage, searchQuery, role) {
      try {
        if(searchQuery == null){
          this.loading = true
        }
        const res = await axiosInstance.get("/attendances",{
            params:{
              page,
              perPage,
              search: searchQuery,
              role
            }
        });
        if (res.status === 200) {
          this.items = res.data;
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

    // async fetchInitialData(user_id) {
    //   try {
    //     this.loading = true;
    //     const res = await axiosInstance.get(`/user/fetch/initial/data?id=${user_id}`);
    //     this.roles = res.data.roles
    //     this.user = res.data.user
    //     return new Promise((resolve) => {
    //       resolve(res.data);
    //     });
    //   } catch (error) {
    //     if (error.response.data) {
    //       this.errors = error.response.data.errors
    //     }
    //   }finally{
    //     this.loading = false;
    //   }
    // },

    async fetchStudents(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.get('/fetch/students',{
            params:{
              group_id:form.group_id,
              section_ids:form.section_ids,
              session_id:form.session_id,
              subject_id:form.subject_id,
            }
        });
        return new Promise((resolve) => {
          this.student_list = res.data
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

    async show(id) {
      try {
        this.loading = true;
        const res = await axiosInstance.get(`/attendances/${id}`);
        this.attendance = res.data
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

    async store(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post('/attendances',form);
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

    async update(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post("/update/attendance",form);
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

    async destroy(id) {
      try {
        const res = await axiosInstance.delete(`/attendances/${id}`);
        if (res.status === 200) {
            let index = this.items.data.findIndex(
              (item)=> item?.id == id
            );
            this.items.data.splice(index,1)
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

    async multipleDelete(selectedIds) {
      try {
        const res = await axiosInstance.delete('/attendance/multiple-delete',{
            params:{
              ids: selectedIds
            }
        });
        if (res.status === 200) {
            this.items.data = this.items.data.filter(
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
