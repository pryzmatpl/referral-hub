<template lang="html">
  <div>
    <div class="card">
      <div class="card-body">
        <div 
          v-bind:class="[applyResponse.state == 'success' ? alertClasses.success : alertClasses.error]"
          v-if="applyResponse.message"
          role="alert"
        >
          {{ applyResponse.message }}
        </div>
        <h1>
          {{ job.title }}
          <button class="btn btn-success float-right col-3" @click="apply">
            Apply
            <font-awesome-icon v-if="loading" :icon="cogIcon" spin />
          </button>
        </h1>
        <h4 class="text-muted">{{ job.fund }} - {{ job.fund }} PLN</h4>
        <small class="badge badge-pill badge-light">REMOTE = {{ job.remote }}</small>
        <small class="badge badge-pill badge-light">RELOCATION = {{ job.relocation }}</small>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-body">
        <h4>Description</h4>
        <div class="row">
          <div class="col-6 text-muted" v-html="job.description"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueChartJs from 'vue-chartjs'
import {
  mixins,
  HorizontalBar
} from 'vue-chartjs'

import {
  faCog
} from '@fortawesome/fontawesome-free-solid'

let lineChartKw = {
  extends: VueChartJs.HorizontalBar,
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
}

export default {
  components:{
    FontAwesomeIcon,
    lineChartKw
  },

  mounted () {
    this.$store.state.backend
      .get('/job/get/' + this.$route.params.id)
      .then(ret => {this.job = ret.data; console.log(ret)})
      .catch(error => console.log("Error (mounted):",error))
  },

  computed: {
    cogIcon: () => faCog
  },

  data () {
    return {
      job: {
        title: 'Title',
        description: 'Desc',
        required_remote: true,
        required_relocation: true,
        required_fund: '10000'
      },
      alertClasses: {
        success: 'alert-success',
        error: 'alert-danger'
      },
      loading: false,
      applyResponse: {}
    }
  },

  methods: {
    apply () {
      this.loading = true;
      const id = this.$route.params.id;
      this.$store.state.backend.post('/api/apply', {
        jobid: id,
        email: this.$store.state.dehashedData.EMAIL
      })
      .then(ret => this.applyResponse = ret.data)
      .then(ret => console.log('applied'))
      .finally(() => this.loading = false)
    }
  }
}
</script>
<style lang="sass" scoped>
  p
    color: gray
    span
      color: black
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
</style>
