import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";
import { useBulkDelete } from "@/stores";

export const useSession = defineStore("session", {
  state: () => ({
    items: [],
    last_session: [],
    errors: {},
    loading: false,
  }),
  getters: {
    getItems: (state)=>{
      return state.items;
    },
  },

  actions: {

    async index() {
      try {
        this.loading = true
        const res = await axiosInstance.get("/sessions");
        if (res.status === 200) {
          this.items = res.data;
          this.last_session = res.data?.data[0] 
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

    // async lastSession() {
    //   try {
    //     this.loading = true;
    //     const res = await axiosInstance.get('/last/session');
    //     this.last_session = res.data.data
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

    async store(form) {
      try {
        this.loading = true;
        const res = await axiosInstance.post('/sessions',form);
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
        const res = await axiosInstance.post("/update/session",form);
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
        const res = await axiosInstance.delete(`/sessions/${id}`);
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

  },
});
