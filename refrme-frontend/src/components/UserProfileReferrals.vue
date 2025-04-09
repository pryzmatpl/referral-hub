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
        { key: 'state', label: 'Status'},
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
        { key: 'state', label: 'Status'},
        { key: 'created_at', label: 'Created At' }
        ]"
    ></b-table>
    <b-modal v-model="modalShow" size="lg" hide-footer scrollable>
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

    this.$nextTick(() => {
      document.querySelector('.modal-dialog').style.zIndex = '1055';
      document.querySelector('.modal-backdrop').style.zIndex = '1045';
    });
  },

  computed: {
    processedReferrals() {
        return this.referrals.map(referral => ({
            ...referral,
            created_at: new Date(referral.created_at).toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: 'UTC' // Optional: use if you want consistent UTC times
        }),
            jobTitle: referral.job.title,
            companyName: referral.job.company.name,
            referrerEmail: referral.user.email
        }));
    },
    processedReferralsSend() {
        return this.referralsSend.map(referral => ({
            ...referral,
            created_at: new Date(referral.created_at).toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: 'UTC' // Optional: use if you want consistent UTC times
        }),
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
      this.jobId = record.jobid
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


