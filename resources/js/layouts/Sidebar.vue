<script setup>
import {onMounted,nextTick } from "vue"
import {useRoute} from "vue-router"
import {useAuth} from "@/stores"
const route = useRoute();
const auth = useAuth();
onMounted(() => {
 $('[data-widget="treeview"]').Treeview('init');
});
</script>

<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link :to="{name: 'user.login'}" class="brand-link text-center">
      <!-- <img
         src="dist/img/AdminLTELogo.png"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: 0.8"
       -->
      <span class="brand-text font-weight-light text-white font-weight-bold"><b style="color: #3cc913;">GCCC</b> <span style="color:#ff8300;">ICT</span></span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <div v-if="auth.loading" class="text-center m-5">
          <span
              class="spinner-border spinner-border-sm mr-1 text-white"
              style="padding: 12px; font-size: 20px;"
          ></span>
          <div class="text-white">Loading....</div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2" v-else>
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >

          <li class="nav-item" v-show="auth.can('dashboard.read')">
            <router-link :to="{name: 'user.dashboard'}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard 
              </p>
            </router-link>
          </li>

          <li class="nav-item" v-show="auth.can('role.read')">
            <a class="nav-link" :class="(route.name=='role.index' || route.name=='role.add' || route.name=='role.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fab fa-critical-role"></i>
              <p>
                Role & Permissions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3" v-show="auth.can('role.create')">
                <router-link :to="{name: 'role.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Role (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'role.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item" v-show="auth.can('student.read') || auth.can('teacher.read') || auth.can('admin.read')">
            <a class="nav-link" :class="(route.name=='student.index' || route.name=='student.add' || route.name=='student.edit' || route.name=='teacher.index' || route.name=='teacher.add' || route.name=='teacher.edit' || route.name=='admin.index' || route.name=='admin.add' || route.name=='admin.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3" v-show="auth.can('student.create')">
                <router-link :to="{name: 'student.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Student (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'student.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Students</p>
                </router-link>
              </li>
              <div class="dropdown-divider ml-5"></div>
              <li class="nav-item ml-3" v-show="auth.can('teacher.create')">
                <router-link :to="{name: 'teacher.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Teacher (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'teacher.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Teachers</p>
                </router-link>
              </li>
              <div class="dropdown-divider ml-5"></div>
              <li class="nav-item ml-3" v-show="auth.can('admin.create')">
                <router-link :to="{name: 'admin.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Admin (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'admin.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Admins</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item" v-show="auth.can('attendance.read')">
            <a class="nav-link" :class="(route.name=='attendance.index' || route.name=='attendance.add' || route.name=='attendance.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fas fa-child"></i>
              <p>
                Attendance
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3" v-show="auth.can('attendance.create')">
                <router-link :to="{name: 'attendance.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Attendance (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'attendance.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance List</p>
                </router-link>
              </li>
            </ul>
          </li>
          
          <li class="nav-item" v-show="auth.can('topic.read')" v-if="(auth.user?.role == 'Admin') || (auth.user?.role == 'Teacher')">
            <a class="nav-link" :class="(route.name=='topic.index' || route.name=='topic.add' || route.name=='topic.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-check-circle"></i>
              <p>
                Assignment Topic
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3" v-show="auth.can('topic.create')">
                <router-link :to="{name: 'topic.add'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Topic (+)</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'topic.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Topic List</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item" v-show="auth.can('topic.read')" v-else-if="(auth.user?.role == 'Student')">
            <a class="nav-link" :class="(route.name=='topic_std.index' || route.name=='topic.add' || route.name=='topic.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-check-circle"></i>
              <p>
                <!-- Student -->
                Assignment Topic
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3">
                <router-link :to="{name: 'topic_std.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Topic List</p>
                </router-link>
              </li>
            </ul>
          </li>

          <!-- <li class="nav-item" v-show="auth.can('assignment.read')" v-if="(auth.user?.role == 'Admin') || (auth.user?.role == 'Teacher')">
            <a class="nav-link" :class="(route.name=='assignment.index' || route.name=='assignment.add' || route.name=='assignment.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                Assignment
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">             
              <li class="nav-item ml-3">
                <router-link :to="{name: 'assignment.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assignment List</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item" v-show="auth.can('assignment.read')" v-if="(auth.user?.role == 'Student')">
            <a class="nav-link" :class="(route.name=='assignment.index' || route.name=='assignment.add' || route.name=='assignment.edit') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                Assignment
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3">
                <router-link :to="{name: 'assignment.index'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>My Assignments</p>
                </router-link>
              </li>
            </ul>
          </li> -->

          <li class="nav-item" v-show="auth.can('report.teacher') || auth.can('report.student') || auth.can('report.attendance') || auth.can('report.assignment')">
            <a class="nav-link" :class="( route.name=='class.report' || route.name=='report.teacher' || route.name=='report.student' || route.name=='report.attendance' || route.name=='report.assignment') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-file"></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3" v-if="auth.user?.role == 'Admin'">
                <router-link :to="{name: 'class.report'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Class Report</p>
                </router-link>
              </li>  
              <li class="nav-item ml-3" v-show="auth.can('report.teacher')">
                <router-link :to="{name: 'report.teacher'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Teacher's Report</p>
                </router-link>
              </li>             
              <li class="nav-item ml-3" v-show="auth.can('report.attendance')">
                <router-link :to="{name: 'report.attendance'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance Report</p>
                </router-link>
              </li>
              <li class="nav-item ml-3" v-show="auth.can('report.assignment')">
                <router-link :to="{name: 'report.assignment'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assignment Report</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item" v-if="auth.user?.role == 'Admin'">
            <a class="nav-link" :class="(route.name=='session.tracking' || route.name=='counsellor.students') ? 'active' : ''" style="cursor: pointer;">
              <i class="nav-icon fa fa-asterisk"></i>
              <p>
                Miscellaneous
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ml-3">
                <router-link :to="{name: 'session.tracking'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Session Tracking</p>
                </router-link>
              </li>
              <li class="nav-item ml-3">
                <router-link :to="{name: 'counsellor.students'}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Counsellor's Students</p>
                </router-link>
              </li>
            </ul>
          </li>


          <li class="nav-item" v-if="auth.role == 'Teacher' && auth.user.is_counsellor">
            <router-link :to="{name: 'teacher.counselling'}" class="nav-link" :class="(route.name=='teacher.counselling' || route.name=='counselling.view_details') ? 'active' : ''">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Counselling
              </p>
            </router-link>
          </li>


          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>


<style>
.disabled {
    opacity: 0.8;
    pointer-events: none;
}
</style>