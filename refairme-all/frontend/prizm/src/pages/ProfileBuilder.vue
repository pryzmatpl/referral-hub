<template lang="pug">
  div
    .card
      .card-body
        .col-xs-12
          h1.card-title(style='color: #B0AFAB;') Your Profile Builder
        .row
          .col-xs-12.col-md-6
            RefairKeywords#profilekeywords(:keywords="profilekeywords" v-on:keywords='updateProfileKeywords')
          .col-xs-12.col-md-6
            //ChartJs(chart-id='kwchart', :height='250', :chart-data='patterndatakw')
        .col-xs-12
          a.btn.btn-info(href='#', @click='saveProfile') Save Profile
    .card.mt-3
      .card-body
        h1(style='color: #B0AFAB;')
          | Profiled Jobs
          font-awesome-icon(v-if='loading', :icon='cogIcon', spin='')
        table.table.table-striped
          thead
            tr.text-muted
              th(scope='col') Id
              th(scope='col') Title
              th(scope='col') Pay
              th(scope='col') Job Profile
          tbody
            tr(v-if='jobs.length == 0')
              td(colspan='4', style='text-align: center') No jobs matched
            tr(v-for='job in jobs', @click='openJobDetails(job.id)')
              th(scope='row') {{job.id}}
              td {{job.title}}
              td {{job.fund[0]}} - {{job.fund[1]}}
              td(style='width: 50%')
                //ChartJs(chart-id='', :height='250', :chart-data='populateWeights(job.weights)')
</template>
<script>
import Vue from 'vue'
import {
  mixins,
  HorizontalBar
} from 'vue-chartjs'
import RefairKeywords from '@/components/Keywords.vue'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faCog
} from '@fortawesome/fontawesome-free-solid'

var ChartJs = {
  extends: HorizontalBar,
  mixins: [mixins.reactiveProp],
  props: ['chartData'],
  data () {
    return {
      options:{
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        }
      }
    }
  },
  mounted () {
    if (this.chartData) {
      this.renderChart(this.chartData, this.options)
    }
  }
};

export default {
  components: {
    RefairKeywords,
    FontAwesomeIcon,
    ChartJs
  },

  computed: {
    cogIcon: () => faCog
  },

  watch: {
    profilekeywords: function(keywords) {
      if(keywords.length != 0){
        // On keywords updated, send it to backend for ai analysis
        this.loading = true

        let params = {
          'eval': keywords.join(),
        }

        this.$store.getters.backend('/eval', {params})
          .then(ret => {
            var dataUpd = {
                labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
                datasets: [{
                  label: 'Your Personal Profile ',
                  backgroundColor: '#a84979',
                  data: ret.data.weightsA.predictions
                }]
              };
            this.patterndatakw = dataUpd
            this.matchProfile()
          })
          .catch(error => console.error(error))
          .finally(() => this.loading = false)
      }
    }
  },

  mounted () {
    const email = this.$store.state.dehashedData.EMAIL

    this.$store.state.backend
      .post('/api/user/getprofile/' + email)
      .then(response => {
        console.log('getprofile')
        if(response.data.status !== 'error')
          this.profilekeywords = response.data.keywords
      })
  },

  data () {
    return {
      profilekeywords: [],
      jobs: [],
      loading: false,
      patterndatakw: {
        labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
        datasets: [{
          label: 'Refair.me Profile ',
          backgroundColor: '#a84979',
          data: [0.05, 0.2, 0.1, 0.5, 0.2, 0.05, 0, 0, 0, 0, 0]
        }]
      }
    }
  },

  methods: {
    populateWeights (weights) {
      return {
        labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
        datasets: [{
          label: 'Refair.me Profile ',
          backgroundColor: '#a84979',
          data: weights
        }]
      }
    },

    updateProfileKeywords (value) {
      if(value.length == 0){
        this.jobs = []
      } else {
        this.loading = true
        this.profilekeywords = value;
      }
    },

    saveProfile () {
      console.log('Saving profile')

      let params = {
        'email': this.$store.state.dehashedData.EMAIL,
        'weights': this.patterndatakw.datasets[0].data,
        'keywords': this.profilekeywords
      }

      this.$store.state.backend
        .post('/api/user/storeprofile', {params})
        .then(response => console.log('Profile saved'))
        .catch(error => console.log(error))
    },

    matchProfile () {
      let params = {
        'passedWeights': JSON.stringify(this.patterndatakw.datasets[0].data)
      }

      this.$store.state.backend
        .get('/matchprofile', {params})
        .then(ret => this.jobs = ret.data)
        .catch(error => console.error(error))
        .finally(() => this.loading = false);
    },

    openJobDetails (jobId) {
      this.$router.push({
        path: `api/job/${jobId}`
      })
    }
  }
}
</script>
<style lang="sass">
  .h1
    margin-bottom: 100px
  tbody tr
    cursor: pointer
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
</style>
