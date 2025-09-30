<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuth,useNotification } from "@/stores";
import VTextInput from "@/common/components/VTextInput.vue";
import VSelectInput from "@/common/components/VSelectInput.vue";
import { Form } from "vee-validate";
import * as yup from "yup";

const schema = yup.object({
  user_type: yup.string(),
  phone: yup.string(),
  roll_no: yup.string(),
  password: yup.string().required().min(6),
});

const auth = useAuth();
const notify = useNotification();
const router = useRouter();
const user_type = ref("TEACHER");
const onSubmit = async (values, { setErrors, resetForm }) => {
    try {
      const res = await auth.login(values);
      if(res){
        router.push({name: "user.dashboard"});
        // window.location.href = '/dashboard'
      }
    } catch (error) {
        notify.Error(error.errors.message[0])
        setErrors(error);
    }
};

</script>

<template>
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <router-link :to="{name: 'user.login'}" class="h1 text-success"><b>GCCC</b> <span class="text-danger">ICT</span></router-link>
    </div>
    <div class="card-body pt-2">
      <h3 class="login-box-msg text-primary">Login</h3>

      <Form
        @submit="onSubmit"
        :validation-schema="schema"
        v-slot="{ errors, isSubmitting, meta , values}"
      >
        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
           
            <VSelectInput 
              name="user_type" 
              class="form-control"
              :options="[
                { label: 'TEACHER', value: 'TEACHER' },
                { label: 'STUDENT', value: 'STUDENT' }
              ]"
            />
        </div>

        <div class="input-group mb-3" v-if="values.user_type=='STUDENT'">
            <div class="input-group-text">
                <span class="fas fa-address-card"></span>
            </div>
            <VTextInput name="roll_no" type="number" placeholder="Roll No: Ex 24140XXXXX"/>

        </div>

        <div class="input-group mb-3" v-else>
            <div class="input-group-text">
                <span class="fas fa-phone"></span>
            </div>
            <VTextInput name="phone" type="text" placeholder="Phone No. Ex: 015XXXXXXXX"/>

        </div>


        <div class="input-group mb-3">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
            <VTextInput name="password" type="password" placeholder="Password"/>
        </div>
        <div class="row">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" />
              <label for="remember"> &nbsp;Remember Me </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" :disabled="isSubmitting" class="btn btn-primary btn-block">
              <div>
                <span>Sign In</span>
                <span v-show="isSubmitting" class="spinner-border spinner-border-sm ml-1 mr-1"></span>
              </div>
            </button>
          </div>
          <!-- /.col -->
        </div>
      </Form>
    </div>
    <!-- /.card-body -->
  </div>
</template>
