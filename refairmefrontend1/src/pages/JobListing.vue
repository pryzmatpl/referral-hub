<template lang="html">
  <div>
    <h2 class="mt-3 mb-4 text-center">My job listing</h2>
    <font-awesome-icon v-if="loading" :icon="loadingIcon" spin class="fa-3x center"></font-awesome-icon>
    <JobListItem
      v-for="job in jobListing"
      :job="job"
      :key="job.id"
      @jobToEdit="selectJob"
      @fetchJobs="getJobs"
    />
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <!-- li class="page-item">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">«</span>
            <span class="sr-only">Previous</span>
          </a>
        </li -->
        <li class="page-item" v-for="o in resultPages" :key="o">
          <a class="page-link" href="#" @click="updateCurrentPage(o - 1)">{{ o }}</a>
        </li>
        <!-- li class="page-item">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">»</span>
            <span class="sr-only">Next</span>
          </a>
        </li -->
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

<script>
import store from '@/store/index.js'
import {mapGetters, mapActions, mapState} from 'vuex'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faCheck,
  faTimes,
  faEdit,
  faTrash,
  faCog
} from '@fortawesome/fontawesome-free-solid'
import JobBuilderAboutJob from '@/components/JobBuilderAboutJob'
import JobListItem from '@/components/JobListItem'

export default {
  components: {
    FontAwesomeIcon,
    JobBuilderAboutJob,
    JobListItem
  },

  computed: {
    ...mapGetters(['jobListing','resultPages']),
    ...mapState(['currentPage']),
    checkIcon: () => faCheck,
    crossIcon: () => faTimes,
    editIcon: () => faEdit,
    deleteIcon: () => faTrash,
    loadingIcon: () => faCog,
  },

  created () {
    this.getJobs()
  },

  watch: {
    'currentPage' () {this.$store.dispatch('getJobs')}
  },

  data () {
    return {
      //jobs:[],
      job: {
        company: { id: 0},
        project: { id: 0}
      },
      loading: false,
      modalShow: false
    }
  },

  methods: {
    ...mapActions([
      'updateCurrentPage'
    ]),
    selectJob (job) {
      this.job = job
      this.$refs.modal.show()
      console.log('job')
      console.log(job)
    },

    getJobs () {
      this.$store.dispatch('getJobs')
    }
    /*
    editJob (id) { // to delete
      this.callBuilder('/job/update/')(id)
    },
    deleteJob (id) { //to delete
      this.callBuilder('/job/delete/')(id)
    },
    callBuilder (endpoint) {
      return arg => { // about to move to store
        this.loading = true

        const handler = jobs => {
          if(endpoint == '/getjobs'){
            this.jobs = []
            console.log(jobs)
            this.jobs = jobs
          } else {
            this.getJobs()
          }
        }

        return this.$store.state.backend
          .get(endpoint + (arg || ''))
          .then(ret => handler(ret.data))
          .catch(error => alert(error.message))
          .finally(() => this.loading = false)
      }
    }
*/
  }
}
</script>
<style lang="sass" scoped>
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
  .red-background
    background-color: red
  .fa-3x
    display: inline-block
    width: 100%
    margin-bottom: 10px
</style>
