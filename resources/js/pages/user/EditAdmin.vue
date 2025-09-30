<script setup>
import {reactive, onMounted} from "vue"
import {useRouter, useRoute} from "vue-router"
import {storeToRefs} from "pinia"
import { useUser,useNotification } from "@/stores";
const router = useRouter();
const route = useRoute();
const pinia_user = useUser();
const notify = useNotification();
const {errors,loading} = storeToRefs(pinia_user);

const formData = new FormData();
const form = reactive({
    id: route.params?.id,
    name: '',
    gender: 'MALE',
    religion: 'ISLAM',
    phone: null,
    email: null,
    image: null,
    status: 1,
});

onMounted(async()=>{
  pinia_user.loading = true
  const resData = await pinia_user.showAdmin(route.params?.id)
  form.id = resData.data.id
  form.name = resData.data?.name
  form.gender = resData.data?.gender
  form.religion = resData.data?.religion
  form.email = resData.data?.email
  form.phone = resData.data?.phone
  form.status = resData.data?.is_verified
  form.image = import.meta.env.VITE_APP_URL+"/"+resData.data.image
})


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

const updateAdmin = async() => {
  formData.append("id",form.id);
  formData.append("name",form.name);
  formData.append("gender",form.gender);
  formData.append("religion",form.religion);
  if(form.phone != null){
    formData.append("phone",form.phone);
  }
  formData.append("email",form.email);
  formData.append("status",form.status ? 1 : 0);

  const resData = await pinia_user.updateAdmin(formData);

  if(resData?.status){
    notify.Success(resData.message);
    form.id = null
    form.name = ''
    form.email = null
    form.phone = null
    form.status = 1

    router.push({name: 'admin.index'})
  }else{
    notify.Error("Something went wrong!");
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
                <h3 class="card-title">Update Admin ({{ form.name }})</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="updateAdmin">
                <div class="card-body">

                  <div class="form-group">
                    <label for="user_name">Admin Name</label>
                    <input type="text" class="form-control" v-model="form.name" id="user_name" placeholder="Enter Admin Name">
                    <span class="text-danger" v-if="errors?.name">{{ errors.name[0] }}</span>
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