import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useDashboard = defineStore("dashboard", {
  state: () => ({
    dashboard: [],
    errors: {},
    loading: false,
  }),

  actions: {
    async index(from, to) {
      try {
        const res = await axiosInstance.get("/dashboard",{
            params:{
              from,
              to
            }
        });
        if (res.status === 200) {
          this.dashboard = res.data;
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
