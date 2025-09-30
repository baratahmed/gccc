<script setup>
import { onMounted, ref, computed} from "vue";
import { useRoute} from "vue-router";
import Datepicker from 'vuejs3-datepicker';  // It's used
import {storeToRefs} from "pinia"
import { debounce } from "lodash";
import { useCounselling, useAuth } from "@/stores";

const auth = useAuth();
const route = useRoute();
const pinia_counselling = useCounselling();

var d = new Date();
const from = ref( new Date(`${d.getFullYear()}-${d.getMonth()+1}-${1}`));
const to = ref(new Date());

onMounted(async()=>{
    await pinia_counselling.viewDetails(from.value, to.value, route.params.id)
})

const getResults = async () => {
    await pinia_counselling.viewDetails(from.value, to.value, route.params.id)
}

const debounceSearch = computed(()=> debounce(getResults,500));

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
        <div v-if="pinia_counselling.view_details == undefined || pinia_counselling.view_details.length == 0" class="col-12">
            <div class="text-center m-5">
                <span
                    class="  spinner-border spinner-border-sm mr-1 text-dark"
                    style="padding: 12px; font-size: 20px;"
                ></span>
                <div>Loading....</div>
            </div>
        </div>

        <div class="col-11 mx-2" v-else>

            <div class="row">       
                
                    <table class="table table-bordered table-hover table-striped ml-5">
                        <thead class="bg-dark">
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Total Classes</th>
                                <th>Present</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
        
                            <tr class="font-weight-bold" v-for="(item, key, index) in pinia_counselling.view_details" :key="index">
                                <td>{{index+1}}</td>
                                <td>{{key}}</td>
                                <td>{{item.total_classes}}</td>
                                <td>{{item.present}}</td>
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