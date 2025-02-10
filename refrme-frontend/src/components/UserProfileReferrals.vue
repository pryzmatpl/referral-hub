<template>
  <div>
    <b-table
        :items="referrals"
        @row-clicked="onRowClicked"
        :fields="['jobsId', 'email', 'status', 'createdAt']"
    ></b-table>
    <b-modal v-model="modalShow" size="lg" hide-footer>
      <JobDetails v-if="modalShow" :jobId="jobId" />
    </b-modal>
  </div>
</template>
<script>
import JobDetails from '@/pages/JobDetails'

export default {
  components: {
    JobDetails
  },

  mounted () {
    this.$store.getters.backend
      .get(`/getreferral/received/${this.$store.getters.dehashedData.EMAIL}`)
      .then(ret => this.referrals = ret.data)
  },

  data () {
    return {
      modalShow: false,
      jobId: 0,
      referrals: []
    }
  },

  methods: {
    onRowClicked (record, index) {
      this.modalShow = true
      this.jobId = record.jobsId
      //this.$router.push(`/job/${record.job_id}`)
    }
  }
}
</script>
<style lang="scss" scoped>
  .modal-dialog {
    max-width: 90%
  }
</style>


