<template>
  <div>
    <h2>Referrals received</h2>
    <b-table
        :items="processedReferrals"
        @row-clicked="onRowClicked"
        :fields="[
        { key: 'jobTitle', label: 'Job Title'},
        { key: 'companyName', label: 'Company'},
        { key: 'referrerEmail', label: 'Referred by'},
        { key: 'created_at', label: 'Created At' }
        ]"
    ></b-table>
    <h2>Referrals sent</h2>
    <b-table
        :items="processedReferralsSend"
        @row-clicked="onRowClicked"
        :fields="[
        { key: 'jobTitle', label: 'Job Title'},
        { key: 'companyName', label: 'Company'},
        { key: 'email', label: 'Referred to'},
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

    this.$store.getters.backend
      .get(`/getreferral/send/${this.$store.getters.dehashedData.USER_ID}`)
      .then(ret => this.referralsSend = ret.data.referrals)
  },

  computed: {
    processedReferrals() {
        return this.referrals.map(referral => ({
            ...referral,
            jobTitle: referral.job.title,
            companyName: referral.job.company.name,
            referrerEmail: referral.user.email
        }));
    },
    processedReferralsSend() {
        return this.referralsSend.map(referral => ({
            ...referral,
            jobTitle: referral.job.title,
            companyName: referral.job.company.name
        }));
    }
  },

  data () {
    return {
      modalShow: false,
      jobId: 0,
      referrals: [],
      referralsSend: []
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


