import { createRouter, createWebHistory } from "vue-router";
import { useAuth } from "@/stores";
import NProgress from "nprogress";
import Dashboard from "@/pages/Dashboard.vue";
import Login from "@/pages/auth/Login.vue";

import {AddRole,EditRole,RoleIndex} from "@/pages/role";
import {AddStudent,EditStudent,StudentIndex,AddTeacher,EditTeacher,TeacherIndex,AddAdmin,EditAdmin,AdminIndex,MyProfile} from "@/pages/user";
import {AddAttendance,EditAttendance,AttendanceIndex} from "@/pages/attendance";
import {AddTopic,EditTopic,TopicIndex,TopicStdIndex} from "@/pages/topic";
import {AddAssignment,EditAssignment,AssignmentIndex,TopicAssignmentsMarking} from "@/pages/assignment";
import {ClassReport, TeacherReport, AttendanceReport, AssignmentReport} from "@/pages/report";
import {SessionTracking} from "@/pages/misc";
import {CounsellorStudents, TeacherCounselling, ViewDetails} from "@/pages/counselling";

const routes = [
    {
      path: "/",
      name: "user.login",
      component: Login,
      meta: { title: "Login", guest: true },
    },
    {
      path: "/dashboard",
      name: "user.dashboard",
      component: Dashboard,
      meta: { title: "Dashboard", requiresAuth: true },
    },


    // Role & Permission
    {
      path: "/roles",
      name: "role.index",
      component: RoleIndex,
      meta: { title: "All Roles", requiresAuth: true },
    },
    {
      path: "/add/role",
      name: "role.add",
      component: AddRole,
      meta: { title: "Add Role", requiresAuth: true },
    },
    {
      path: "/edit/role/:id",
      name: "role.edit",
      component: EditRole,
      meta: { title: "Edit Role", requiresAuth: true },
    },  

    // User Management
    {
      path: "/students",
      name: "student.index",
      component: StudentIndex,
      meta: { title: "All Students", requiresAuth: true },
    },
    {
      path: "/add/student",
      name: "student.add",
      component: AddStudent,
      meta: { title: "Add Student", requiresAuth: true },
    },
    {
      path: "/edit/student/:id",
      name: "student.edit",
      component: EditStudent,
      meta: { title: "Edit Student", requiresAuth: true },
    },  
    {
      path: "/teachers",
      name: "teacher.index",
      component: TeacherIndex,
      meta: { title: "All Teachers", requiresAuth: true },
    },
    {
      path: "/add/teacher",
      name: "teacher.add",
      component: AddTeacher,
      meta: { title: "Add Teacher", requiresAuth: true },
    },
    {
      path: "/edit/teacher/:id",
      name: "teacher.edit",
      component: EditTeacher,
      meta: { title: "Edit Teacher", requiresAuth: true },
    },  
    {
      path: "/admins",
      name: "admin.index",
      component: AdminIndex,
      meta: { title: "All Admins", requiresAuth: true },
    },
    {
      path: "/add/admin",
      name: "admin.add",
      component: AddAdmin,
      meta: { title: "Add Admin", requiresAuth: true },
    },
    {
      path: "/edit/admin/:id",
      name: "admin.edit",
      component: EditAdmin,
      meta: { title: "Edit Admin", requiresAuth: true },
    },  
    {
      path: "/my/profile/:id",
      name: "user.profile",
      component: MyProfile,
      meta: { title: "My Profile", requiresAuth: true },
    }, 


    // Attendance Management
    {
      path: "/attendances",
      name: "attendance.index",
      component: AttendanceIndex,
      meta: { title: "All Attendances", requiresAuth: true },
    },
    {
      path: "/add/attendance",
      name: "attendance.add",
      component: AddAttendance,
      meta: { title: "Add Attendance", requiresAuth: true },
    },
    {
      path: "/edit/attendance/:id",
      name: "attendance.edit",
      component: EditAttendance,
      meta: { title: "Edit Attendance", requiresAuth: true },
    },  


    // **   Assignment Management  ** //

    // Assignment Topics
    {
      path: "/topics",
      name: "topic.index",
      component: TopicIndex,
      meta: { title: "All Topics", requiresAuth: true },
    },
    {
      path: "/add/topic",
      name: "topic.add",
      component: AddTopic,
      meta: { title: "Add Topic", requiresAuth: true },
    },
    {
      path: "/edit/topic/:id",
      name: "topic.edit",
      component: EditTopic,
      meta: { title: "Edit Topic", requiresAuth: true },
    },
    {
      path: "/student/topics",
      name: "topic_std.index",
      component: TopicStdIndex,
      meta: { title: "All Topics", requiresAuth: true },
    },

    {
      path: "/topic/assignments/marking/:id",
      name: "topic.assignments.marking",
      component: TopicAssignmentsMarking,
      meta: { title: "Specific Topic Assignments", requiresAuth: true },
    }, 


    // Assignment
    {
      path: "/assignments",
      name: "assignment.index",
      component: AssignmentIndex,
      meta: { title: "All Assignments", requiresAuth: true },
    },
    {
      path: "/add/assignment/:topic_id",
      name: "assignment.add",
      component: AddAssignment,
      meta: { title: "Add Assignment", requiresAuth: true },
    },
    {
      path: "/edit/assignment/:id",
      name: "assignment.edit",
      component: EditAssignment,
      meta: { title: "Edit Assignment", requiresAuth: true },
    },  


    // Report
    {
      path: "/class/report",
      name: "class.report",
      component: ClassReport,
      meta: { title: "Class Report", requiresAuth: true },
    },
    {
      path: "/report/teacher",
      name: "report.teacher",
      component: TeacherReport,
      meta: { title: "Teacher's Report", requiresAuth: true },
    },
    {
      path: "/report/attendance",
      name: "report.attendance",
      component: AttendanceReport,
      meta: { title: "Attendance Report", requiresAuth: true },
    },
    {
      path: "/report/assignment",
      name: "report.assignment",
      component: AssignmentReport,
      meta: { title: "Assignment Report", requiresAuth: true },
    },
   

    // Counselling
    {
      path: "/counsellor/students",
      name: "counsellor.students",
      component: CounsellorStudents,
      meta: { title: "Counsellor Students", requiresAuth: true },
    },

    {
      path: "/teacher/counselling",
      name: "teacher.counselling",
      component: TeacherCounselling,
      meta: { title: "Students Under Counsellor", requiresAuth: true },
    },

    {
      path: "/counselling/view_details/:id",
      name: "counselling.view_details",
      component: ViewDetails,
      meta: { title: "View Details", requiresAuth: true },
    },




    // Miscellaneous
    {
      path: "/session/tracking",
      name: "session.tracking",
      component: SessionTracking,
      meta: { title: "Session Year", requiresAuth: true },
    },

    



];

const router = createRouter({
  history: createWebHistory(),
  routes,
  linkActiveClass: 'active',
  scrollBehavior() {
    // always scroll to top
    return { top: 0, behavior: "smooth" };
  },
});

const DEFAULT_TITLE = "404";

router.beforeEach((to, from, next) => {
  document.title = to.meta.title || DEFAULT_TITLE;
  NProgress.start();

  const auth = useAuth();

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!auth.getAuthStatus) {
      next({ name: "user.login" });
    } else {
      next();
    }
  } else if (to.matched.some((record) => record.meta.guest)) {
    if (auth.getAuthStatus) {
      next({ name: "user.dashboard" });
    } else {
      next();
    }
  } else {
    next();
  }
});

router.afterEach(() => {
  NProgress.done();
});
export default router;
