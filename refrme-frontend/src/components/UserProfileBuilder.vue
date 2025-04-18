<template>
  <div>
    <div class="col-xs-12">
      <h1 class="card-title" style="color: #B0AFAB">User: {{ userInput.firstname }} {{ userInput.lastname }}</h1>
      <div class="row">
        <div class="col">
          <input class="form-control" type="text" v-model="userInput.firstname" placeholder="enter your first name" />
        </div>
        <div class="col">
          <input class="form-control" type="text" v-model="userInput.lastname" placeholder="enter your last name" />
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-xs-12 col-md-6">
        <h4>1. Select your job status</h4>
        <Slider
          class="ml-4"
          name="expslider"
          :min="0"
          :max="3"
          tooltip="none"
          :interval="1"
          v-model="userInput.jobStatus"
          piecewise="true"
          :piecewiseStyle="piecewiseStyle"
          :piecewiseActiveStyle="piecewiseActiveStyle"
          piecewiseLabel="true"
          :data="['Not looking','Not looking, but open','Casually looking','Actively looking']"
        />
        {{ userInput.jobStatus || 'Not looking' }}
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-xs-12 col-md-8">
        <h4>2. Enter your skills</h4>
        <RefairKeywords
          :keywords="userInput.keywords"
          @keywords="updateProfileKeywords"
          :skills="userInput.skills"
          @skills="updateSkills"
        />
      </div>
      <div class="col-xs-12 col-md-6">
        <!-- ChartJs(chart-id='kwchart', :height='250', :chart-data='patterndatakw') -->
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-12">
        <h4>3. What is the notice period in Your current job</h4>
        <input class="form-control col-6" v-model="userInput.noticePeriod" type="text" placeholder="eg. one month from the start of the next month" />
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-12">
        <h4>4. When is the best time to contact you</h4>
        <textarea class="form-control col-6" v-model="userInput.availability" rows="3" placeholder="available monday between 12 and 2, tuesday, wednesday, thursday after 4 pm"></textarea>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-12">
        <h4>5. Show matching jobs above this salary</h4>
        <b-input-group append="PLN">
          <b-form-input class="col-6" v-model="userInput.expectedSalary" type="number" placeholder="eg. 7000 (don't price yourself out of market)" />
        </b-input-group>
        <p v-if="userInput.expectedSalary > 99999 && userInput.expectedSalary < 999999">Are you Elon Musk?</p>
        <p v-if="userInput.expectedSalary >= 999999">Try the dark web</p>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-12">
        <a class="btn btn-info" href="#" @click="saveProfile">Save Profile</a>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, reactive, watch, onMounted } from 'vue'
import { faCog } from '@fortawesome/fontawesome-free-solid'
import RefairKeywords from '@/components/Keywords.vue'
import JobListItem from '@/components/JobListItem'
import Slider from 'vue-slider-component'

import Vue from 'vue'
import {
  mixins,
  HorizontalBar
} from 'vue-chartjs'


/*
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
};*/

export default {
  props: ['exp'],

  components: {
    RefairKeywords,
    
    //ChartJs,
    JobListItem,
    Slider
  },

  computed: {
    cogIcon: () => faCog,
    email: vm => decodeURIComponent(vm.$store.state.dehashedData.EMAIL),
  },

  watch: {
    filteredJobs: function(jobs) {
      this.$emit('jobs', jobs)
    },

    'userInput.keywords': function(keywords) {
      //TODO: EDIT when keywords supported

      if(keywords?.length !== 0){
        this.$emit('loading', true)

        let params = {
          'eval': keywords?.join(),
        }

        this.$store.getters.backend('/eval', {params})
          .then(ret => {
            console.log(ret)
            var dataUpd = {
                labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
                datasets: [{
                  label: 'Your Personal Profile ',
                  backgroundColor: '#a84979',
                  data: ret.data.predictions
                }]
              };
            this.patterndatakw = dataUpd
            this.matchProfile()
          })
          .catch(error => console.error(error))
          .finally(() => this.$emit('loading', false))
      }
    }
  },

  mounted () {
    const unique_id = this.$store.state.dehashedData.USER_ID

    this.$store.state.backend
      .post('/api/user/getprofile/' + unique_id)
      .then(response => {
        console.log('getprofile')
        console.log(response)
        //if(response.data.status !== 'error')
          this.userInput = response.data
          this.userInput.exp = this.exp
      })
  },

  data () {
    return {
      userInput: {
        firstname: '',
        lastname: '',
        keywords: [],
        skills: [],
        noticePeriod: '',
        availability: '',
        expectedSalary: '',
        jobStatus: ''
      },
      jobs: [],
      patterndatakw: {
        labels: ['Backend', 'Full Stack', 'Mobile/Embedded', 'Testing', 'Frontend', 'Dev Ops', 'Business Intelligence', 'IT Trainee', 'Project Management', 'Support', 'UX Designer', 'Business Analyst', 'Other'],
        datasets: [{
          label: 'Refair.me Profile ',
          backgroundColor: '#a84979',
          data: [0.05, 0.2, 0.1, 0.5, 0.2, 0.05, 0, 0, 0, 0, 0]
        }]
      },
      piecewiseStyle: {
        "backgroundColor": "#ccc",
        "visibility": "visible",
        "width": "12px",
        "height": "12px"
      },
      piecewiseActiveStyle: {
        "backgroundColor": "#3498db"
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
        this.userInput.keywords = value;
      }
    },

    updateSkills (value) {
      this.userInput.skills = value
    },

    saveProfile () {
      console.log('Saving profile')
      let params = {
        'unique_id': this.$store.state.dehashedData.USER_ID,
        'weights': this.patterndatakw.datasets[0].data,
        ...this.userInput
      }

      this.$store.state.backend
        .post('/api/user/storeprofile', { params }, {
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }

        )
        .then(response => alert('Profile saved'))
        .catch(error => console.log(error))
    },

    matchProfile () {
      let params = {
        'passedWeights': JSON.stringify(this.patterndatakw.datasets[0].data)
      }

      this.$store.state.backend
        .get('/matchprofile', {params})
        .then(ret => {
          this.jobs = ret.data
          console.log(ret)
        })
        .catch(error => console.error(error))
    },

    openJobDetails (jobId) {
      this.$router.push({
        path: `api/job/${jobId}`
      })
    }
  }
}
</script>
<style lang="scss" scoped>
@use '@/assets/settings.scss' as settings;

.h1 {
  margin-bottom: 100px;
}

h4 {
  color: $primaryColor;
}

tbody tr {
  cursor: pointer;
}

.card {
  box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1);
  border: 0;
}
</style>
