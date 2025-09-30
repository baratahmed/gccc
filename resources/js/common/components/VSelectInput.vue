<script setup>
import { Field } from "vee-validate";
defineProps(['modelValue','name','options'])
defineEmits(['update:modelValue'])
</script>

<template>
  <Field :name="name" v-slot="{ errors, field, meta }">
    <select
      class="form-select"
      :class="{
        'is-valid': meta.valid && meta.touched,
        'is-invalid': !meta.valid && meta.touched
      }"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      v-bind="{ ...$attrs, ...field }"
    >
      <option value="">-- Select User Type --</option>
      <option 
        v-for="(opt, index) in options" 
        :key="index" 
        :value="opt.value"
      >
        {{ opt.label }}
      </option>
    </select>

    <span class="text-danger" v-show="errors">{{ errors[0] }}</span>
  </Field>
</template>

<style>
.input-group>.custom-file, 
.input-group>.custom-select, 
.input-group>.form-control, 
.input-group>.form-control-plaintext {
  width: 70%;
}
</style>
