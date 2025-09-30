<script setup>
import { onMounted,reactive} from "vue";
import {storeToRefs} from "pinia"
import { useRouter } from "vue-router";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { useTopic, useAuth } from "@/stores";
import {onlyDateFormat} from "@/common/Helpers/helper.js"
const auth = useAuth();
const router = useRouter();
const pinia_topic = useTopic();
const {loading} = storeToRefs(pinia_topic);

import {Table, TableHead, TableRow, TableData} from "@/common/components/Table"
import {PrimaryButton, DeleteButton} from "@/common/components/Form"

const form = reactive({
    group: null,
    section: null,
    shift: null,
    session: null,
});

onMounted(async()=>{
    form.group = auth.user?.group;
    form.section = auth.user?.section;
    form.shift = auth.user?.shift;
    form.session = auth.user?.session;
    await pinia_topic.indexForStd(form);
})

const getResults = async (page = 1) => {
    await pinia_topic.index(form, page)
}

function goToAssignmentSubmitPage(topic){
    router.push({name: 'assignment.add', params: {topic_id: topic?.id}});
}


</script>

<template>
   <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <h4 class="text-center m-3">All Assignment Topics</h4>
          </div>
          <div class="col-12">
            <div v-if="loading" class="text-center m-5">
                <span
                    class="spinner-border spinner-border-sm mr-1 text-dark"
                    style="padding: 12px; font-size: 20px;"
                ></span>
                <div>Loading....</div>
            </div>
            <div class="card" v-else>

              <div class="card-body">
                <Table v-if="pinia_topic.getItems?.data?.length > 0">
                    <template #tableHead>            
                        <TableHead>Topic Name</TableHead>
                        <TableHead>Instructions</TableHead>
                        <TableHead>Due Date</TableHead>                       
                        <TableHead>Action</TableHead>
                    </template>
                    <TableRow v-for="(topic,index) in pinia_topic.getItems?.data" :key="index">
                        <TableData>{{topic.topic}}</TableData>
                        <TableData>{{topic.instructions}}</TableData>
                        <TableData>{{onlyDateFormat(topic.due_date)}}</TableData>
                        <TableData>
                            <div class="d-flex" style="gap:4px">
                                <PrimaryButton type="button" @click.prevent="goToAssignmentSubmitPage(topic)">
                                    Submit Assignment
                                </PrimaryButton>
                            </div>
                        </TableData>
                    </TableRow>
                </Table>
                <div class="col-12" v-else> 
                  <h5 class="text-center text-danger" >No Data Found</h5>
                </div>
                <Bootstrap5Pagination
                    :data="pinia_topic.getItems"
                    @pagination-change-page="getResults"
                />

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
</template>

<style scoped>

</style>
