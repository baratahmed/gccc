<script setup>
import {reactive, onMounted, ref, watch, computed} from "vue"
import {useRouter, useRoute} from "vue-router"
import {storeToRefs} from "pinia"
import { useUser,useNotification, useSimple } from "@/stores";
const pinia_simple = useSimple();
const router = useRouter();
const route = useRoute();
const pinia_user = useUser();
const notify = useNotification();
const {errors,loading} = storeToRefs(pinia_user);
const {simple_sections} = storeToRefs(pinia_simple);

const formData = new FormData();
const form = reactive({
    id: route.params?.id,
    name: '',
    name_bn: '',
    f_name: '',
    m_name: '',
    gender: 'MALE',
    religion: 'ISLAM',
    session_id: '999',
    roll_no: null,
    adm_roll_no: null,
    phone: null,
    email: null,
    group_id: '999',
    section_ids: null,
    image: null,
    status: 1,
});


onMounted(async()=>{
  pinia_user.loading = true
  pinia_simple.fetchSimpleAllSessions()
  pinia_simple.fetchSimpleAllGroups()
  const resData = await pinia_user.showStudent(route.params?.id)
  form.id = resData.data.id
  form.name = resData.data?.name
  form.name_bn = resData.data?.name_bn
  form.f_name = resData.data?.f_name
  form.m_name = resData.data?.m_name
  form.gender = resData.data?.gender
  form.religion = resData.data?.religion
  form.email = resData.data?.email
  form.phone = resData.data?.phone
  form.session_id = resData.data?.session_id
  form.roll_no = resData.data?.roll_no
  form.adm_roll_no = resData.data?.adm_roll_no
  form.group_id = resData.data?.group_id
  form.section_ids = resData.data?.section_ids || []
  form.status = resData.data?.is_verified
  form.image = import.meta.env.VITE_APP_URL+"/"+resData.data.image

})

watch(
  ()=>[form.group_id],
  async() => {
    await pinia_simple.fetchSimpleSections(form)
  }
)

const formattedSections = computed(() =>
  simple_sections.value.map(t => ({
    id: t.id,
    label: `${t.name}-${t.shift}`
  }))
)

const loadUserImage = (e) => {
    const reader = new FileReader();
    reader.onload = (event) => {
      form.image = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
    formData.append("image",e.target.files[0]);
}

const changeStatus = () => {
    form.status = !form.status;
}

const updateStudent = async() => {
  formData.append("id",form.id);
  formData.append("name",form.name);
  formData.append("name_bn",form.name_bn);
  formData.append("f_name",form.f_name);
  formData.append("m_name",form.m_name);
  formData.append("roll_no",form.roll_no);
  formData.append("adm_roll_no",form.adm_roll_no);
  formData.append("gender",form.gender);
  formData.append("religion",form.religion);
  formData.append("group_id",form.group_id);
  formData.append("section_ids",form.section_ids);
  formData.append("session_id",form.session_id);
  if(form.phone != null){
    formData.append("phone",form.phone);
  }
  formData.append("email",form.email);
  formData.append("status",form.status ? 1 : 0);

  const resData = await pinia_user.updateStudent(formData);

  if(resData?.status){
    notify.Success(resData.message);
    form.id = null
    form.name = ''
    form.email = null
    form.phone = null
    form.status = 1

    router.push({name: 'student.index'})
  }else{
    notify.Error(resData.message);
  }
}
</script>

<template>
    <section class="content pt-3">
      <div class="container-fluid">
        <div v-if="loading" class="text-center m-5">
            <span
                class="spinner-border spinner-border-sm mr-1 text-dark"
                style="padding: 12px; font-size: 20px;"
            ></span>
            <div>Loading....</div>
        </div>
        <div class="row" v-else>
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Student ({{ form.name }})</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="updateStudent">
                <div class="card-body">

                  <div class="form-group">
                    <label for="user_name">Student Name</label>
                    <input type="text" class="form-control" v-model="form.name" id="user_name" placeholder="Enter Student Name">
                    <span class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_name_bn">নাম (বাংলায়)</label>
                    <input type="text" class="form-control" v-model="form.name_bn" id="user_name_bn" placeholder="নাম বাংলায় লিখুন">
                    <span class="text-danger" v-if="errors?.name_bn">{{ errors.name_bn[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="f_name">Father's Name</label>
                    <input type="text" class="form-control" v-model="form.f_name" id="f_name" placeholder="Enter Father's Name">
                    <span class="text-danger" v-if="errors?.f_name">{{ errors.f_name[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="m_name">Mother's Name</label>
                    <input type="text" class="form-control" v-model="form.m_name" id="m_name" placeholder="Enter Mother's Name">
                    <span class="text-danger" v-if="errors?.m_name">{{ errors.m_name[0] }}</span>
                  </div>                  

                  <div class="form-group">
                    <label for="user_phone">Mobile No</label>
                    <input type="text" class="form-control" v-model="form.phone" id="user_phone" placeholder="Enter Mobile Number">
                    <span class="text-danger" v-if="errors?.phone">{{ errors.phone[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_email">Email Address</label>
                    <input type="email" class="form-control" v-model="form.email" id="user_email" placeholder="Enter Email Address">
                    <span class="text-danger" v-if="errors?.email">{{ errors.email[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_gender">Select Gender</label>
                    <select v-model="form.gender" id="user_gender" class="form-control">
                      <option value="MALE">MALE</option>
                      <option value="FEMALE">FEMALE</option>
                      <option value="OTHER">OTHER</option>
                    </select>
                    <span class="text-danger" v-if="errors?.gender">{{ errors.gender[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_religion">Select Religion</label>
                    <select v-model="form.religion" id="user_religion" class="form-control">
                      <option value="ISLAM">ISLAM</option>
                      <option value="HINDUISM">HINDUISM</option>
                      <option value="CHRISTIANITY">CHRISTIANITY</option>
                      <option value="BUDDHISM">BUDDHISM</option>
                    </select>
                    <span class="text-danger" v-if="errors?.religion">{{ errors.religion[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_roll_no">Roll No</label>
                    <input type="number" class="form-control" v-model="form.roll_no" id="user_roll_no" placeholder="Enter Roll No">
                    <span class="text-danger" v-if="errors?.roll_no">{{ errors.roll_no[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="user_adm_roll_no">Admission Roll No</label>
                    <input type="number" class="form-control" v-model="form.adm_roll_no" id="user_adm_roll_no" placeholder="Enter Admission Roll No">
                    <span class="text-danger" v-if="errors?.adm_roll_no">{{ errors.adm_roll_no[0] }}</span>
                  </div>

                  <div class="form-group" >
                    <label for="user_group">Select Group</label>
                    <select v-model="form.group_id" id="user_group" class="form-control">
                      <option value="999">Select a Group</option>
                      <option :value="group.id" v-for="group in pinia_simple.simple_all_groups">{{ group.name }}</option>
                    </select>
                    <span class="text-danger" v-if="errors?.group_id">{{ errors.group_id[0] }}</span>
                  </div>

                  <div class="form-group" >
                    <label for="user_section">Select Section ( With Shift ) </label>
                     <v-select
                       v-model="form.section_ids"
                      :options="formattedSections"
                       label="label"
                      :multiple="true"
                      :reduce="option => option.id"
                      placeholder="Select Sections"
                    />
                    <span class="text-danger" v-if="errors?.section_ids">{{ errors.section_ids[0] }}</span>
                  </div>                  

                  <div class="form-group" >
                    <label for="user_session">Session</label>
                    <select v-model="form.session_id" id="user_session" class="form-control">
                      <option value="999">Select a Session</option>
                      <option v-for="session in pinia_simple.simple_all_sessions" :key="session.id" :value="session.id">
                        {{ session.name }} ({{ session.type }})
                      </option>
                    </select>
                    <span class="text-danger" v-if="errors?.session_id">{{ errors.session_id[0] }}</span>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputFile">Image </label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" @change="loadUserImage" class="custom-file-input" id="exampleInputFile">
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

                  <div class="form-group py-3">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" @change="changeStatus" :checked="form.status" id="status">
                      <label class="custom-control-label" for="status">Status ({{ form.status ? 'Active' : 'Inactive' }})</label>
                    </div>
                  </div>

                </div>


                <div class="form-group mx-3">
                  <!-- <button type="submit" class="btn btn-primary" :disabled="true">Update</button> -->
                  <button type="submit" class="btn btn-primary" :disabled="loading">Update</button>
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