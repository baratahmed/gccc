<script setup>
import {reactive} from "vue"
import {useRouter} from "vue-router"
import {storeToRefs} from "pinia"
import { useTopic,useNotification } from "@/stores";
import Datepicker from 'vuejs3-datepicker';

const router = useRouter();
const pinia_topic = useTopic();
const notify = useNotification();
const {errors,loading} = storeToRefs(pinia_topic);

const form = reactive({
    group: 'SCIENCE',
    section: 'S1',
    shift: 'DAY',
    session: '2024-2025',
    due_date: new Date(),
    topic:  null,
    instructions: null,
    points: null,
    image: null,
});

const extraFrom = reactive({
    scienceShow: true,
    bsShow: false,
    humShow: false,
});

function changeSections(){
  if(form.group == 'SCIENCE'){
    form.shift = null
    extraFrom.scienceShow = true
    extraFrom.bsShow = false
    extraFrom.humShow = false
  }else if(form.group == 'BUSINESS STUDIES'){
    form.shift = 'DAY'
    extraFrom.scienceShow = false
    extraFrom.bsShow = true
    extraFrom.humShow = false
    form.section = 'BS1'
  }else if(form.group == 'HUMANITIES'){
    form.shift = 'DAY'
    extraFrom.scienceShow = false
    extraFrom.bsShow = false
    extraFrom.humShow = true
    form.section = 'H1'
  }
}

const loadAdditionalImage = (e) => {
    const reader = new FileReader();
    reader.onload = (event) => {
      form.image = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
    formData.append("image",e.target.files[0]);
}


const storeTopic = async() => {
  
  const resData = await pinia_topic.store(form);

    if(resData?.status){
      notify.Success(resData.message);
      router.push({name: 'topic.index'})
    }else{
      notify.Error("Something went wrong!");
    }
}
</script>

<template>
    <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Topic</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="storeTopic">
                <div class="card-body">

                  <div class="form-group">
                    <label for="user_group">Select Group</label>
                    <select v-model="form.group" @change="changeSections()" id="user_group" class="form-control">
                      <option value="SCIENCE">SCIENCE</option>
                      <option value="BUSINESS STUDIES">BUSINESS STUDIES</option>
                      <option value="HUMANITIES">HUMANITIES</option>
                    </select>
                    <span class="text-danger" v-if="errors?.group">{{ errors.group[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_section">Select Section</label>
                    <select v-model="form.section" id="user_section" class="form-control">
                      <option value="S1" v-show="extraFrom.scienceShow">S1</option>                     
                      <option value="S2" v-show="extraFrom.scienceShow">S2</option>                     
                      <option value="S3" v-show="extraFrom.scienceShow">S3</option>                     
                      <option value="BS1" v-show="extraFrom.bsShow">BS1</option>                     
                      <option value="BS2" v-show="extraFrom.bsShow">BS2</option>                     
                      <option value="H1" v-show="extraFrom.humShow">H1</option>                     
                      <option value="H2" v-show="extraFrom.humShow">H2</option>                     
                    </select>
                    <span class="text-danger" v-if="errors?.section">{{ errors.section[0] }}</span>
                  </div>

                  <div class="form-group" v-show="extraFrom.bsShow || extraFrom.humShow">
                    <label for="user_shift">Select Shift</label>
                    <select v-model="form.shift" id="user_shift" class="form-control">
                      <option value="DAY">DAY</option>                     
                      <option value="EVENING">EVENING</option>                                        
                    </select>
                    <span class="text-danger" v-if="errors?.shift">{{ errors.shift[0] }}</span>
                  </div>                  

                  <div class="form-group">
                    <label for="user_session">Session</label>
                    <input type="text" class="form-control" v-model="form.session" id="user_session" placeholder="Enter Session">
                    <span class="text-danger" v-if="errors?.session">{{ errors.session[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="due_date">Due Date</label><br>
                    <datepicker 
                        v-model="form.due_date"
                        placeholder="Due Date"
                        :disabled-dates="{
                            to: new Date(),
                        }"
                        iconColor="red"
                        iconWidth="18"
                        iconHeight="18">
                      </datepicker>
                    <span class="text-danger" v-if="errors?.due_date">{{ errors.due_date[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_topic">Assignment Topic</label>
                    <input type="text" class="form-control" v-model="form.topic" id="user_topic" placeholder="Enter Topic">
                    <span class="text-danger" v-if="errors?.topic">{{ errors.topic[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea id="instructions" rows="5" class="form-control" v-model="form.instructions" placeholder="Enter instructions about assignment..."></textarea>
                    <span class="text-danger" v-if="errors?.instructions">{{ errors.instructions[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_points">Points</label>
                    <input type="number" class="form-control" v-model="form.points" id="user_points" placeholder="Enter Points (Eg - 10)" >
                    <span class="text-danger" v-if="errors?.points">{{ errors.points[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputFile">Additional Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" @change="loadAdditionalImage" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <span class="text-danger" v-if="errors?.image">{{ errors.image[0] }}</span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="preview">
                        <img :src="form.image" v-if="form.image" alt="" width="100px">
                      </div>
                  </div> 

                </div>

                <div class="form-group mx-3">
                  <button type="submit" class="btn btn-primary" :disabled="loading">Submit</button>
                </div>


              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>





</template>