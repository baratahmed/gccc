import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useFileManager = defineStore("file_manager", {
  state: () => ({
    file_loading: false,
    fileable_id: 0,
    items: [],
    file_ids: [],
  }),
  getters: {
    getItems: (state)=>{
      return state.items;
    },
  },
  // persist: {
  //   paths: ["file_ids"],
  // },
  actions: {
    async fileUpload(formData) {
      try {
        this.file_loading = true
        const res = await axiosInstance.post(`/file_manager/upload`, formData);
        this.file_ids.push(res.data)
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.file_loading = false
      }
    },
    async fetchFiles(fileable_id) {
      try {
        this.file_loading = true
        const res = await axiosInstance.get(`/file_manager/read/${fileable_id}`);
        if (res.status === 200) {
          this.items = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.file_loading = false
      }
    },

    async removeFileFromClient(lastModified,size) {
      try {
        this.file_loading = true
        const res = await axiosInstance.delete(`/file_manager/${lastModified}/${size}`);
        if(res.data){
          let index = this.file_ids.findIndex((item)=> {
               return item == res.data 
            }
          );
          this.file_ids.splice(index,1)
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }finally{
        this.file_loading = false
      }
    },

    async removeFile(fileId) {
      try {
        const res = await axiosInstance.delete(`/file_manager/${fileId}`);
        if (res.status === 200) {
            await this.fetchFiles(this.fileable_id);
            return res;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

  },
});
