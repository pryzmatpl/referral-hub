<!--
  - Copyright (c) 2024 Pryzmat sp. z o.o. (Pryzmat LLC)
  - All rights reserved.
  - 15.12.2024, 14:18
  - JobListItem.vue
  - referral-hub
  -
  - This software and its accompanying documentation are protected by copyright law and international treaties.
  - Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
  - is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
  -->

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { computed, ref , defineProps} from 'vue'
import { faEdit, faTrash, faQuestionCircle } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
  job: {
    type: Object,
    default: null
  }
})

const route = useRoute()
const router = useRouter()
const modalShow = ref(false)

const editIcon = faEdit
const deleteIcon = faTrash
const infoIcon = faQuestionCircle

const isJobListing = computed(() => route.path === '/jobs')
const isUserAllowed = computed(() => useStore().state.dehashedData.CURRENT_ROLE === 'admin')
const formattedContractType = computed(() => 'TEST') // Placeholder, replace with actual logic

// Methods
const onRowClick = () => {
  router.push(`/job/${props.job.id}`)
}

const deleteJob = async (id) => {
  try {
    await useStore().state.backend.get(`/job/delete/${id}`)
    emit('fetchJobs')
  } catch (error) {
    alert(error.message)
  }
}

const switchWarningHighlight = (event, hovering) => {
  event.currentTarget.parentNode.parentNode.style.backgroundColor = hovering ? 'rgba(255,0,0,0.3)' : ''
}

const switchJobHighlight = (event, hovering) => {
  event.currentTarget.style.backgroundColor = hovering ? 'rgba(57,143,168, 0.2)' : ''
}

const groupZeros = (value) => value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')

const emit = defineEmits(['jobToEdit', 'fetchJobs'])

const keywords = computed(() => {
  return (props.job != null) ? props.job?.keywords?.split(',') : "";
});

</script>

<template>
  <div>
    <div
        class="card mb-3 shadow"
        style="cursor: pointer"
        @mouseover="switchJobHighlight($event, true)"
        @mouseout="switchJobHighlight($event, false)"
    >
      <div class="card-body" @click="onRowClick">
        <div class="row">
          <div class="col-12 col-sm-2">
            <!-- <img :src="job.company.logo" width="120px" /> -->
          </div>
          <div class="col-12 col-sm-6">
            <h4>{{ job.jobtitle }}</h4>
            <p v-html="job.description"></p>
            <!-- <p>{{ job.company.name }}</p> -->
          </div>
          <div class="col-12 col-sm-4" style="text-align: right" v-b-tooltip.html.bottom="'Get a reward of up to this amount if your referral is hired'">
            <div class="row">
              <div class="col">
                <div>
                  <!-- <h4 style="display: inline-block; text-align: right">{{ groupZeros(job.fund[0]) }} - {{ groupZeros(job.fund[1]) }}</h4> -->
                  <p class="float-right">PLN</p>
                </div>
                <p>{{ formattedContractType }}</p>
              </div>
            </div>
            <div class="row" style="color: #FF0000; text-align: right">
              <div class="col">
                <div>
                  <font-awesome-icon :icon="infoIcon" style="color:black" class="mr-1" />
                  <p style="display: inline-block" class="mr-1">REWARD:</p>
                  <!-- <h4 style="display: inline-block; text-align: right">{{ groupZeros(job.fund[0] * 0.25) }}</h4> -->
                  <p class="float-right">PLN</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="row">
          <div class="col-3" style="border-right: 1px solid rgba(0,0,0,0.1)">
            <p>Where:</p>
            <p class="small">{{ job.location }}</p>
          </div>
          <div class="col-4" style="border-right: 1px solid rgba(0,0,0,0.1)">
            <p>From apply to offer:</p>
            <b-progress :max="100" height="2rem" show-value :variant="job.duration < 22 ? 'success' : 'warning'">
              <b-progress-bar :value="job.duration">{{ job.duration }} days</b-progress-bar>
            </b-progress>
          </div>
          <div class="col-5">
            <p>Technologies:</p>
            <button class="btn tag" v-for="keyword in keywords" :key="keyword">{{ keyword }}</button>
          </div>
        </div>
      </div>
      <div class="card-footer" v-if="isJobListing && isUserAllowed">
        <font-awesome-icon
            class="float-right"
            :icon="deleteIcon"
            @mouseover="switchWarningHighlight($event, true)"
            @mouseout="switchWarningHighlight($event, false)"
            @click="deleteJob(job.id)"
            v-b-tooltip="'Delete without warning'"
            style="cursor: pointer"
        />
        <font-awesome-icon
            class="float-right"
            :icon="editIcon"
            style="margin: 0 15px; cursor: pointer"
            @click="emit('jobToEdit', job)"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.tag {
  background-color: #4a90e2;
  color: white;
  padding: 3px 8px;
  margin: 2px;
  font-size: 15px;
  border-radius: 30px;
}

.red-background {
  background-color: red;
}

.fa-3x {
  display: inline-block;
  width: 100%;
  margin-bottom: 10px;
}

.shadow {
  box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1);
  border: 0;
}
</style>