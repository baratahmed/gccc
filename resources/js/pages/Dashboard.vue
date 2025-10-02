<script setup>
import { Chart, Grid, Bar, Tooltip } from 'vue3-charts'
import { onMounted, ref, computed} from "vue";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useDashboard, useAuth } from "@/stores";

const auth = useAuth();
const pinia_dashboard = useDashboard();
const {dashboard} = storeToRefs(pinia_dashboard);

var d = new Date();
const from = ref( new Date(`${d.getFullYear()}-${d.getMonth()+1}-${1}`));
const to = ref(new Date());
const chartData = ref({})

onMounted(async()=>{
    await pinia_dashboard.index(from.value, to.value);

    chartData.value = Object.entries(pinia_dashboard.dashboard).map(([subject, values]) => ({
        name: subject,
        total: Number(values?.total_classes ?? 0),
        present: Number(values?.present ?? 0)
    }))
})

const getResults = async () => {
    await pinia_dashboard.index(from.value, to.value)
}

const debounceSearch = computed(()=> debounce(getResults,500));

chartData.value = Object.entries(pinia_dashboard.dashboard).map(([subject, values]) => ({
    name: subject,
    total: Number(values?.total_classes ?? 0),
    present: Number(values?.present ?? 0)
}))
  
const margin = ref({ top: 20, right: 20, bottom: 20, left: 20 })

const axis = ref({
  primary: { type: "band" },
  secondary: {
    domain: ["dataMin", "dataMax + 1"],
    type: "linear",
    ticks: 5
  }
})

const direction = ref("horizontal")


</script>
<template lang="">
    <div class="row">
        <div class="col-12 my-4">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <div class="tb_search d-flex">
                        <datepicker
                            v-model="from"
                            @input="debounceSearch"
                            placeholder="From"
                            :disabled-dates="{
                                from: new Date(),
                            }"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                        <span class="mr-2"></span>
                        <datepicker 
                            v-model="to"
                            @input="debounceSearch"
                            placeho lder="To"
                            :disabled-dates="{
                                from: new Date(),
                            }"
                            iconColor="red"
                            iconWidth="18"
                            iconHeight="18">
                        </datepicker>
                    </div>
                </div>       
            </div>
        </div>
       
        <div v-if="dashboard == undefined || dashboard.length == 0" class="col-12">
            <div class="text-center m-5">
                <span
                    class="  spinner-border spinner-border-sm mr-1 text-dark"
                    style="padding: 12px; font-size: 20px;"
                ></span>
                <div>Loading....</div>
            </div>
        </div>

        <div class="col-11 mx-2" v-else>        
            <div class="row" v-if="auth.user.role != 'Student'">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{dashboard.total_teachers}}</h3>
                        <h5>Total Teachers</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{dashboard.total_students}}</h3>

                        <h5>Total Students</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>                
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{dashboard.total_sections}}</h3>

                        <h5>Total Sections</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{dashboard.total_faculties}}</h3>

                        <h5>Total Faculties</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <!-- <h3>53<sup style="font-size: 20px">%</sup></h3> -->
                            
                            <h3>{{dashboard.total_assignments}}</h3>
                            <h5>Total Assignments</h5>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{dashboard.total_attendances}}</h3>

                        <h5>Total Attendances</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{dashboard.active_users}}</h3>

                        <h5>Active Users (Now)</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6"> 
                    <!-- small box -->
                    <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{dashboard.inactive_users}}</h3>

                        <h5>Inactive Users (Now)</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <router-link :to="{name: 'user.dashboard'}" class="small-box-footer" :class="{ disabled: auth.user?.role == 'Employee' }">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
            </div>
            <div class="row" v-else>

                    <div class="ml-5">
                         <Chart
                            :size="{ width: 600, height: 400 }"
                            :data="chartData"
                            :margin="margin"
                            :direction="direction"
                            :axis="axis"
                        >
                            <template #layers>
                                <Grid strokeDasharray="3,3" />
                                <Bar :dataKeys="['name', 'total']" :barStyle="{ fill: '#17078A' }" />
                                <Bar :dataKeys="['name', 'present']" :barStyle="{ fill: '#06C222' }" />
                                </template>

                                <template #widgets>
                                <Tooltip
                                    :config="{
                                    total: { color: '#17078A', label: 'Total Classes' },
                                    present: { color: '#06C222', label: 'Present' }
                                    }"
                                />
                            </template>
                        </Chart>
                    </div>
                    <table class="table table-bordered table-hover table-striped ml-5">
                        <thead class="bg-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Total Classes</th>
                                <th>Present</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="font-weight-bold text-center" v-for="(item, index) in dashboard" :key="index">
                                <td>{{index+1}}</td>
                                <td>{{item.subject_name}}</td>
                                <td>{{item.total_classes ?? '-'}}</td>
                                <td>{{item.present ?? '-'}}</td>
                                <td>{{item.percentage}} %</td>
                            </tr>                            
                        </tbody>
                    </table>
            </div>
        </div>    
    </div>
</template>

<style scoped>
.disabled {
    opacity: 0.5;
    pointer-events: none;
}
</style>