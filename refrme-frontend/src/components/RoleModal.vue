<script setup>
import { closeModal, confirmModal } from '@kolirt/vue-modal'
import {SimpleModal} from "@kolirt/vue-modal";

const props = defineProps({
  test: {}
})
</script>

<template>
  <SimpleModal title=" " size="md">
    <h3 class="text-center ">Select your role:</h3>
    <div class="d-flex justify-content-center my-5">
      <button @click="confirmModal({ value: 'developer' })" class="role shadow">Developer</button>
      <button @click="confirmModal({ value: 'recruiter' })" class="role shadow">Recruiter</button>
    </div>  
  </SimpleModal>
</template>

<style lang="scss" scoped>
button {
  width: 40%;
  height: 10rem;
  margin: 20px;
  background-color: #42bff4;
  color: white;
  font-size: 1.8rem;
  border-radius: 10px;
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}


</style>