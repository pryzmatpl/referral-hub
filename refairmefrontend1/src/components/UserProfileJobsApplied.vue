<template lang="pug">
  div
    b-table(
        :items="jobs"
        @row-clicked="onRowClicked"
        :fields="['jobsId','job.title','createdAt']"
      )
    b-modal(
      v-model="modalShow"
      size="lg"
      hide-footer
    )
      JobDetails(
        v-if="modalShow"
        :jobId="jobId"
      )
</template>
<script>
import JobDetails from '@/pages/JobDetails'

export default {
  components: {
    JobDetails
  },
  
  mounted () {
    this.$store.getters.backend
      .get(`/getapply/${this.$store.getters.dehashedData.EMAIL}`)
      .then(ret => this.jobs = ret.data)
  },

  data () {
    return {
      jobId: '',
      jobs:[]
    }
  },

  methods: {
    onRowClicked (record, index) {
      this.modalShow = true
      this.jobId = record.jobsId
    }
  }
}
</script>

