<template lang="pug">
div
  .card.mb-3.shadow(
    style="cursor: pointer"
    v-on:mouseover="switchJobHighlight($event, true)"
    v-on:mouseout="switchJobHighlight($event, false)"
    )
    .card-body(@click="onRowClick")
      .row
        .col-12.col-sm-2
          img(:src="job.company.logo" width="120px")
        .col-12.col-sm-6
          h4 {{job.title}}
          p {{job.company.name}}
        .col-12.col-sm-4(style="text-align: right" v-b-tooltip.html.bottom="'Get a reward of up to this amount if your referral is hired'")
          .row
            .col
              div
                h4(style="display: inline-block; text-align: right") {{job.fund[0] | groupZeros}} - {{job.fund[1] | groupZeros}}
                p.float-right PLN
              p {{formattedContractType}}
          .row(style="color: #FF0000;text-align: right")
            .col
              div()
                font-awesome-icon(:icon='infoIcon' style="color:black").mr-1
                p(style="display: inline-block").mr-1 REWARD:
                h4(style="display: inline-block;text-align: right")  {{job.fund[0] * 0.25 | groupZeros}}
                p.float-right PLN
      hr
      .row
        .col-3(style="border-right: 1px solid rgba(0,0,0,0.1)")
          p Where:
          p.small {{job.location}}
        .col-4(style="border-right: 1px solid rgba(0,0,0,0.1)")
          p From apply to offer:
          b-progress(:max="100" height="2rem" show-value :variant="job.duration < 22 ? 'success' : 'warning'")
            b-progress-bar(
              :value="job.duration"
            ) {{job.duration}} days
          //.progress(style="height: 1.5rem")
            .progress-bar.progress-bar-striped.bg-success(
              role='progressbar', 

              :aria-valuenow='30',
              aria-valuemin='0',
              aria-valuemax='100'
            ) {{job.duration}}

        .col-5
          p Technologies:
          //span.tag(v-for="keyword in job.keywords.slice(0,4)")
          button.btn.tag(v-for="keyword in job.keywords.slice(0,4)") {{keyword}}
    .card-footer(v-if="isJobListing && isUserAllowed")
      font-awesome-icon.float-right(:icon='deleteIcon'
        v-on:mouseover="switchWarningHighlight($event, true)"
        v-on:mouseout="switchWarningHighlight($event, false)"
        v-on:click="deleteJob(job.id)"
        v-b-tooltip="'Delete without warning'"
        style="cursor: pointer"
        )
      font-awesome-icon.float-right(
        :icon='editIcon'
        style="margin: 0 15px; cursor: pointer"
        @click="$emit('jobToEdit', job)"
      )
</template>
<script>
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faCheck,
  faTimes,
  faEdit,
  faTrash,
  faCog,
  faQuestionCircle
} from '@fortawesome/fontawesome-free-solid'
import JobBuilderAboutJob from '@/components/JobBuilderAboutJob'

export default {
  components: {
    FontAwesomeIcon,
    JobBuilderAboutJob
  },

  props: {
    job: {
      type: Object,
      required: true
    }
  },

  computed: {
    editIcon: () => faEdit,
    deleteIcon: () => faTrash,
    infoIcon: () => faQuestionCircle,
    isJobListing: vm => vm.$route.path == '/jobs',
    isUserAllowed: vm => vm.$store.state.dehashedData.CURRENT_ROLE === 'admin',
    formattedContractType: vm => vm.job.contractType.join(', ')
  },

  filters: {
    groupZeros: function(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")
    }
  },

  data (){
    return {
      //company: '',
      modalShow: false
    }
  },

  methods: {
    onRowClick () {
      this.$router.push(`/job/${this.job.id}`)
    },

    deleteJob (id) {
      this.$store.state.backend
        .get(`/job/delete/${id}`)
        .then(ret => this.$emit('fetchJobs'))
        .catch(error => alert(error.message))
        //.finally(() => this.loading = false)
    },

    switchWarningHighlight (event, hovering) {
      event
      .currentTarget
      .parentNode
      .parentNode
      .style
      .backgroundColor = hovering ? 'rgba(255,0,0,0.3)' : ''
    },

    switchJobHighlight (event, hovering) {
      event
      .currentTarget
      .style
      .backgroundColor = hovering ? 'rgba(57,143,168, 0.2)' : ''
    }
  }
}
</script>
<style lang="sass" scoped>
  .tag
    background-color: #4a90e2
    color: white
    padding: 3px 8px
    margin: 2px
    font-size: 15px
    border-radius: 30px
  .red-background
    background-color: red
  .fa-3x
    display: inline-block
    width: 100%
    margin-bottom: 10px
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0
</style>


