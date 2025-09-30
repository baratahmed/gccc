import { defineStore } from "pinia";
import axiosInstance from "@/services/AxiosService";

export const useSimple = defineStore("simple", {
  state: () => ({
    simple_counsellors: [],
    simple_all_sessions: [],
    simple_groups: [],
    simple_all_sections: [],
    simple_theory_sections: [],
    simple_sections: [],
    simple_all_subjects: [],
    simple_subjects: [],
    simple_teachers: [],
    simple_dates: [],
    errors: {},
    loading: false,
  }),
  getters: {
    getItems: (state)=>{
      return state.items;
    },
  },

  actions: {

      async fetchSimpleCounsellors() {
        try {
          const res = await axiosInstance.get('/simple/counsellors');
          if (res.status === 200) {
            this.simple_counsellors = res.data;
            return res.data;
          }
        } catch (error) {
          if (error.response.data) {
            throw error.response.data.errors;
          }
        }
      },

     async fetchSimpleAllSessions() {
      try {
        const res = await axiosInstance.get("/simple/all_sessions");
        if (res.status === 200) {
          this.simple_all_sessions = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleAllGroups() {
      try {
        const res = await axiosInstance.get("/simple/all_groups");
        if (res.status === 200) {
          this.simple_all_groups = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleGroups(group_id, subject_id) {
      try {
        const res = await axiosInstance.get('/simple/groups',{
          params:{
            group_id,
            subject_id
          }
        });
        if (res.status === 200) {
          this.simple_groups = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleAllSections() {
      try {
        const res = await axiosInstance.get("/simple/all_sections",{
        });
        if (res.status === 200) {
          this.simple_all_sections = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleTheorySections(session_id) {
      try {
        const res = await axiosInstance.get(`/simple/theory_sections/${session_id}`);
        if (res.status === 200) {
          this.simple_theory_sections = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleSections(form) {
      try {
        const res = await axiosInstance.get('/simple/sections',{
          params:{
              session_id:form.session_id,
              group_id:form.group_id,
              type:form.type,
              subject_id:form.subject_id,
            }
        });
        if (res.status === 200) {
          this.simple_sections = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
      
    },

    async fetchSimpleAllSubjects() {
      try {
        const res = await axiosInstance.get("/simple/all_subjects");
        if (res.status === 200) {
          this.simple_all_subjects = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleSubjects(group_id) {
      try {
        const res = await axiosInstance.get(`/simple/subjects/${group_id}`);
        if (res.status === 200) {
          this.simple_subjects = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSimpleTeachers() {
      try {
        const res = await axiosInstance.get("/simple/teachers");
        if (res.status === 200) {
          this.simple_teachers = res.data;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },

    async fetchSettingByKeyName(key) {
      try {
        const res = await axiosInstance.get(`/simple/setting/${key}`);
        if (res.status === 200) {
          this.simple_dates = res.data.value;
          return res.data;
        }
      } catch (error) {
        if (error.response.data) {
          throw error.response.data.errors;
        }
      }
    },


  },
});
