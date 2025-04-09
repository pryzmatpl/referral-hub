<template>
  <div>
    <b-table
        :items="processedJobs"
        @row-clicked="onRowClicked"
        :fields="[
        { key: 'title', label: 'Job Title' },
        { key: 'companyName', label: 'Company'},
        { key: 'createdAt', label: 'Created At' }
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
      .get(`/getapply/${this.$store.getters.dehashedData.USER_ID}`)
      .then(ret => this.jobs = ret.data)
  },

  computed: {
    processedJobs() {
        return this.jobs.map(job => ({
            ...job,
            companyName: job.company ? job.company.name : 'No Company'
        }));
    }
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

