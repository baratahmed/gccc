<script setup>
import {reactive, onMounted,} from "vue"
import {useRouter, useRoute} from "vue-router"
import {storeToRefs} from "pinia"
import { useAssignment, useFileManager,useNotification, useAuth } from "@/stores";
const router = useRouter();
const route = useRoute();
const auth = useAuth();
const file_manager = useFileManager();
const pinia_assignment = useAssignment();
const notify = useNotification();
const {file_loading} = storeToRefs(file_manager);
const form = reactive({
  topic_id: route.params.topic_id,
  student_id: auth.user?.id,
  file_ids: file_manager.file_ids
});

const sendToUpload = async (file, xhr, formData) => {
    try {
        formData.append("lastModified",file[0]?.lastModified);
        formData.append("size",file[0]?.size);
        await file_manager.fileUpload(formData);
    } catch (error) {
        
    }
}

const onFileRemove = async (file) =>{
  try {
        const res = await file_manager.removeFileFromClient(file.file.lastModified,file.file.size);
    } catch (error) {

    }
}

const onFileAdd = () => {
  // file_manager.file_count++
}


const storeAssignment = async() => {
  const resData = await pinia_assignment.store(form);
  form.topic_id = null;
  form.student_id = null;
  file_manager.$reset();
  if(resData?.status){
    notify.Success(resData.message);
    router.push({name: 'topic_std.index'})
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
                <h3 class="card-title">Add New Assignment</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form @submit.prevent="storeAssignment">
                <div class="card-body">

                 <div class="form-group">
                  <label for="">Upload Photos (MAX: 20)</label>
                  <DropZone
                    :maxFiles="20"
                    url="http://localhost:5000/item"
                    :uploadOnDrop="true"
                    :multipleUpload="true"
                    :parallelUpload="1"
                    :acceptedFiles="['png','image','jpg','jpeg','gif','ttif','webp']"
                    @sending="sendToUpload"
                    @addedFile="onFileAdd"
                    @removedFile="onFileRemove"
                  />
                </div>

                </div>

                <div class="form-group mx-3">
                  <button type="submit" class="btn btn-primary" :disabled="loading || file_loading">Submit</button>
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