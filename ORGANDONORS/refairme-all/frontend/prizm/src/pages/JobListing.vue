<template lang="pug">
  .card
    .card-body
      font-awesome-icon.fa-3x.center(v-if="loading", :icon='loadingIcon' spin)
      b-modal#editModal(
        v-model="modalShow"
        title="Edit this job"
        size="lg"
        hide-footer
      )
        JobBuilderAboutJob(
          :companyId="selectedJobCompany"
          :projectId="selectedJobProject"
          :jobToEdit="selectedJob"
          v-on:fetch="getJobs"
          v-on:closeModal="modalShow = false"
        )
      .table-responsive
        table.table.table-striped
          thead
            tr
              th Job Title
              th Keywords
              th Location
              th Salary
              th Remote
              th Relocation pack
              th Operations
          tbody
            tr(v-for="job in jobs")
              td {{job.title}}
              td {{job.keywords}}
              td {{job.location}}
              td {{job.fund[0]}} - {{job.fund[1]}}
              td
                font-awesome-icon(:icon="job.remote ? checkIcon : crossIcon")
              td
                font-awesome-icon(:icon="job.relocation ? checkIcon : crossIcon")
              td
                font-awesome-icon(
                  :icon='editIcon'
                  style="margin: 0 15px"
                  @click="selectedJob = job"
                  v-b-modal="'editModal'"
                )
                font-awesome-icon(:icon='deleteIcon'
                  v-on:mouseover="switchWarningHighlight($event, true)"
                  v-on:mouseout="switchWarningHighlight($event, false)"
                  v-on:click="deleteJob(job.id)"
                  v-b-tooltip="'Delete without warning'"
                )
</template>
<script>
import store from '../../prizm-vuex'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faCheck,
  faTimes,
  faEdit,
  faTrash,
  faCog
} from '@fortawesome/fontawesome-free-solid'
import JobBuilderAboutJob from '@/components/JobBuilderAboutJob'

export default {
  components: {
    FontAwesomeIcon,
    JobBuilderAboutJob
  },

  computed: {
    checkIcon: () => faCheck,
    crossIcon: () => faTimes,
    editIcon: () => faEdit,
    deleteIcon: () => faTrash,
    loadingIcon: () => faCog
  },

  created () {
    this.getJobs()
  },

  data () {
    return {
      jobs:[],
      loading: false,
      modalShow: false,
      selectedJobCompany: 1,
      selectedJobProject: 2,
      selectedJob: {}
    }
  },

  methods: {
    getJobs () {
      this.callBuilder('/job/get/all')()
    },

    editJob (id) {
      this.callBuilder('/job/update/')(id)
    },

    deleteJob (id) {
      this.callBuilder('/job/delete/')(id)
    },

    callBuilder (endpoint) {
      return arg => { // about to move to store
        this.loading = true

        const handler = jobs => {
          if(endpoint == '/job/get/all'){
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
    },

    switchWarningHighlight (event, hovering) {
      event
      .currentTarget
      .parentNode
      .parentNode
      .style
      .backgroundColor = hovering ? 'rgba(255,0,0,0.5)' : ''
    },
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
