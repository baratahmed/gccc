import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";
import { useBulkDelete } from "@/stores";

export const useTopic = defineStore("topic", {
  state: () => ({
    items: [],
    simple_topics: [],
    topic: [],
    errors: {},
    loading: false,
  }),
  getters: {
    getItems: (state)=>{
      return state.items;
    },
  },

  actions: {

     async simpleTopics() {
      try {
        this.loading = true
        const res = await axiosInstance.get("/simple/topics");
        if (res.status === 200) {
          this.simple_topics = res.data;
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


    async index(page, perPage, searchQuery) {
      try {
        if(searchQuery == null){
          this.loading = true
        }
        const res = await axiosInstance.get("/topics",{
            params:{
              page,
              perPage,
              search: searchQuery
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

    async indexForStd(form) {
      try {        
        this.loading = true
        const res = await axiosInstance.get("/student/topics",{
            params:{
              group: form.group,
              section: form.section,
              shift: form.shift,
              session: form.session,
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

    async show(id) {
      try {
        this.loading = true;
        const res = await axiosInstance.get(`/topics/${id}`);
        this.topic = res.data
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
        const res = await axiosInstance.post('/topics',form);
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
        const res = await axiosInstance.post("/update/topic",form);
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
        const res = await axiosInstance.delete(`/topics/${id}`);
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
        const res = await axiosInstance.delete('/topic/multiple-delete',{
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
