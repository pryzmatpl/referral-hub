<!--
  - Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
  - All rights reserved.
  - 15.12.2024, 14:21
  - JobListing.vue
  - referral-hub
  -
  - This software and its accompanying documentation are protected by copyright law and international treaties.
  - Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
  - is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
  -->

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { faCheck, faTimes, faEdit, faTrash, faCog } from '@fortawesome/free-solid-svg-icons'

// Components
import JobBuilderAboutJob from '@/components/JobBuilderAboutJob.vue'
import JobListItem from '@/components/JobListItem.vue'

const store = useStore()

// Computed properties
const jobListing = computed(() => store.getters.jobListing)
const resultPages = computed(() => store.getters.resultPages)
const currentPage = computed(() => store.state.currentPage)
const appliedJobs = computed(() => store.getters.jobApplied)

// Icons
const checkIcon = faCheck
const crossIcon = faTimes
const editIcon = faEdit
const deleteIcon = faTrash
const loadingIcon = faCog

// Data
const job = ref({
  company: { id: 0 },
  project: { id: 0 }
})
const loading = ref(false)
const modalShow = ref(false)

// Lifecycle hooks
onMounted(() => {
  getJobs()
  appliedJobs.value = store.getters.jobApplied || [];
})

// Watchers
watch(currentPage, () => {
  store.dispatch('getJobs')
})

// Methods
const selectJob = (jobData) => {
  job.value = jobData
  modalShow.value = true // Note: Using v-model on b-modal will handle this
  console.log('job')
  console.log(jobData)
}

const getJobs = () => {
  store.dispatch('getJobs')
}

const updateCurrentPage = (page) => {
  store.dispatch('updateCurrentPage', page)
}

// Note: The following methods have been removed as they're not needed with the current structure:
// - editJob
// - deleteJob
// - callBuilder

</script>

<template>
  <div>
    <div class="d-flex justify-content-end" v-if="$store.state.role === 'recruiter'"><router-link to='/job/add'><button class="btn btn-primary">+ Create new job</button></router-link></div>
    <h2 class="mt-3 mb-4 text-center">My job listing</h2>
    <font-awesome-icon v-if="loading" :icon="loadingIcon" spin class="fa-3x center"></font-awesome-icon>
    <JobListItem
        v-for="job in jobListing"
        :job="job"
        :key="job.id"
        @jobToEdit="selectJob"
        @fetchJobs="getJobs"
        :applied="appliedJobs.some(appliedJob => appliedJob.id === job.id)"
    />
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item" v-for="o in resultPages" :key="o">
          <a class="page-link" href="#" @click.prevent="updateCurrentPage(o - 1)">{{ o }}</a>
        </li>
      </ul>
    </nav>
    <b-modal
        ref="modal"
        v-model="modalShow"
        :title="'Edit job nr ' + job.id"
        size="lg"
        hide-footer
    >
      <JobBuilderAboutJob
          v-if="modalShow"
          :companyId="job.company.id"
          :projectId="job.project.id"
          :jobToEdit="job"
          @closeModal="modalShow = false"
      />
    </b-modal>
  </div>
</template>


<style lang="scss" scoped>
.card {
  box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1);
  border: 0;
}

.red-background {
  background-color: red;
}

.fa-3x {
  display: inline-block;
  width: 100%;
  margin-bottom: 10px;
}
</style>