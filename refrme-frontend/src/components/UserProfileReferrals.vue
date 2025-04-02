<template>
  <div>
    <b-table
        :items="processedReferrals"
        @row-clicked="onRowClicked"
        :fields="[
        { key: 'jobTitle', label: 'Job Title'},
        { key: 'email', label: 'Company'},
        { key: 'created_at', label: 'Created At' }
        ]"
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
      .get(`/getreferral/received/${this.$store.getters.dehashedData.USER_ID}`)
      .then(ret => this.referrals = ret.data.referrals)
  },

  computed: {
    processedReferrals() {
        return this.referrals.map(referral => ({
            ...referral,
            jobTitle: referral.job.title
        }));
    }
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


