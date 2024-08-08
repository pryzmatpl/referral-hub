<template>
  <div>
    <h1>About the project</h1>
    <hr />
    <form id="aboutProject" @submit.prevent="emitProjectToParent">
      <div class="row">
        <div class="col">
          <label>Choose existing project:</label>
        </div>
      </div>
      <div class="form-row">
        <select v-model="selectedProjectIndex">
          <option value="" disabled>choose project</option>
          <option v-for="(value, index) in projects" :key="index" :value="index">
            {{ value.name }}
          </option>
        </select>
      </div>
      <div class="row">
        <div class="col">
          <p>OR create new project below:</p>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="form-group">
            <label for="projectTitle">Project title</label>
            <input
                class="form-control"
                v-model="name"
                name="projectTitle"
                type="text"
                placeholder="Enter new project title"
            />
          </div>
          <div class="form-group">
            <label for="whyWorkOnProject">Why would someone want to work on the project?</label>
            <textarea
                class="form-control"
                name="whyWorkOnProject"
                v-model="description"
                rows="5"
                type="text"
                placeholder="The project is designed to solve the problem about recruitment. It has a small agile team who are building a game changer. If you want to work in a fast moving environment and are solutions orientated this is for you "
            ></textarea>
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-group">
            <label for="projectMethodology">Work methodology</label>
            <div
                class="form-check"
                v-for="method in ['issue tracking tool','knowledge repository','code reviews','pair programming','unit testing','TDD','integration testing','Agile','Lean','Scrum','Waterfall']"
                :key="method"
            >
              <input
                  class="form-check-input"
                  type="checkbox"
                  name="checkbox"
                  v-model="methodology"
                  :value="method"
              />
              <label class="form-check-label">{{ method }}</label>
            </div>
          </div>
          <div class="form-group">
            <label for="perks">Perks</label>
            <div class="form-check" v-for="perk in perks" :key="perk.name">
              <input
                  class="form-check-input"
                  type="checkbox"
                  name="checkbox"
                  v-model="selectedPerks"
                  :value="perk"
              />
              <label class="form-check-label">{{ perk.name }}</label>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>How many people on project?</label>
                <b-button-group class="w-100">
                  <b-button
                      class="w-100"
                      v-for="teamSizeOption in ['<10','<50','100+']"
                      :key="teamSizeOption"
                      type="button"
                      variant="outline-secondary"
                      @click="staff = teamSizeOption"
                      :class="{ active: staff === teamSizeOption }"
                  >
                    {{ teamSizeOption }}
                  </b-button>
                </b-button-group>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>What stage is project at?</label>
                <multiselect
                    v-model="stage"
                    :options="['greenfield','ongoing development','maintenance']"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    placeholder="Pick a type"
                />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="projectStack">What is the project techstack?</label>
            <textarea
                class="form-control"
                name="projectStack"
                v-model="stack"
                rows="5"
                type="text"
                placeholder="Please describe technology stack"
            ></textarea>
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-row m-0">
            <label>Weekly work breakdown</label>
          </div>
          <div class="form-row">
            <div class="col-8">
              <div class="form-group row" v-for="(value, key, index) in breakdown" :key="index">
                <label class="col-5">{{ value.label }}</label>
                <div class="col-4 pl-0 pr-0">
                  <Slider
                      name="expslider"
                      :process-style="{ backgroundColor: labelColors[key] }"
                      :min="1"
                      :max="100"
                      tooltip="none"
                      :interval="1"
                      v-model="value.value"
                  />
                </div>
                <div class="col">
                  <span>{{ formattedBreakdown[key].value }} <small>%</small></span>
                </div>
              </div>
            </div>
            <div class="col-4">
              <chart chart-id="chart" :chart-data="chartData" :options="options" />
            </div>
          </div>
        </div>
      </div>
      <button class="btn btn-info float-right" type="submit">Next</button>
    </form>
  </div>
</template>
<script>
import validation from '../validation.js'
import Multiselect from 'vue-multiselect'
import Slider from 'vue-slider-component'
import {
  Doughnut,
  PolarArea
} from 'vue-chartjs'
//

import {
  faTag
} from '@fortawesome/fontawesome-free-solid'

var chart = {
  extends: Doughnut,
  // mixins: [mixins.reactiveProp],
  props: ['options'],
  mounted() {
    if (this.chartData) {
      this.renderChart(this.chartData, this.options)
    }
  }
};

export default {
  components: {
    chart,
    FontAwesomeIcon: () => import('@fortawesome/vue-fontawesome'),
    faTag: () => import('@fortawesome/fontawesome-free-solid').then(({faTag}) => faTag),
    Multiselect,
    Slider
  },

  props: {
    companyId: {
      required: true,
      type: Number
    }
  },

  computed: {
    tagIcon: () => faTag,
    breakdownReduced: vm => vm.breakdown.reduce((prevValue, currValue) => prevValue + currValue.value, 0),
    formattedBreakdown: vm => {
      return vm.breakdown.map(obj => {
        return {label: obj.label, value: Math.round((obj.value / vm.breakdownReduced) * 100 )}
      })
    }
  },

  watch: {
    companyId: {
      handler () {
        this.fetchAllProjects(this.companyId)
      },
      immediate: true
    },

    selectedProjectIndex () {
      if(this.selectedProjectIndex !== ''){
        let selectedId = this.projects[this.selectedProjectIndex]
        this.projectId = selectedId.id
        this.name = selectedId.name
        this.description = selectedId.description
        this.staff = selectedId.staff
        this.stage = selectedId.stage
        this.stack = selectedId.stack
        this.breakdown = selectedId.breakdown
        this.methodology = selectedId.methodology
      }
    },

    name: 'matchWithExistingProjects',
    description:'matchWithExistingProjects',
    staff: 'matchWithExistingProjects',
    stage: 'matchWithExistingProjects',
    stack: 'matchWithExistingProjects',
    breakdown: 'matchWithExistingProjects',
    methodology: 'matchWithExistingProjects',
    
    breakdown: {
      handler: function(n,o){
        this.updateChart(n,o)
      },
      deep: true
    }
  },

  mounted () {
    this.updateChart(1)
  },

  data () {
    return {
      projects: [],
      projectId: '',
      name: '',
      selectedProjectIndex: '',
      description: '',
      staff: '',
      stage: '',
      stack: '',
      methodology: [],
      breakdown: [
        {label: 'NewFeatures' , value: 20},
        {label: 'Bug fixing' , value: 20},
        {label: 'Self-development' , value: 20},
        {label: 'Meetings' , value: 20},
        {label: 'Support' , value: 20},
        {label: 'Documentation' , value: 20}
      ],
      selectedPerks: [],
      perks: [
        {name: 'Free beverages', available: true},
        {name: 'Free snacks', available: false},
        {name: 'Free lunch', available: false},
        {name: 'Kitchen/canteen', available: true},
        {name: 'In-house trainings', available: true},
        {name: 'Training budget', available: true},
        {name: 'Office gym', available: false},
        {name: 'Shower', available: false},
        {name: 'Sports subscription', available: true},
        {name: 'Bike parking', available: false},
        {name: 'Car parking', available: true},
        {name: 'In-house hack-days', available: false},
        {name: 'Team events', available: false},
        {name: 'Play Room', available: true},
        {name: 'Private health care', available: true},
        {name: 'Kindergarten', available: false}
      ],
      chartData: null,
      options:{
        responsive: true,
        maintainAspectRatio: true,
        legend: {
          display: false
        },
        layout: {
          padding: 5
        }
      },
      labelColors: ['#10B13A','#EC2E15','#EC8015','#117992','#FFB770','#007bff']
    }
  },

  methods: {
    fetchAllProjects (id = '') {
      if(id !== ''){
        this.$store.state.backend
          .get('/project/get/all/' + id)
          .then(ret => {this.projects = ret.data; console.log(ret.data);})
          .catch(error => console.error(error))
      }
    },

    updateChart (v) {
      if(v != ''){
        this.chartData = {
          labels: [
            'New features',
            'Bug fixing',
            'Self-development',
            'Meetings',
            'Support',
            'Documentation'
          ],
          datasets: [{
            backgroundColor: this.labelColors,
            data: [
              this.formattedBreakdown[0].value,
              this.formattedBreakdown[1].value,
              this.formattedBreakdown[2].value,
              this.formattedBreakdown[3].value,
              this.formattedBreakdown[4].value,
              this.formattedBreakdown[5].value
            ]
          }]
        }
      }
    },

    validateForm () {
      var validated = true;

      $("form#aboutProject .form-group label:not(.form-check-label)")
      .each(function(){
        if(validation.validateField($(this)) == false){
          validated = false
          window.scroll(0,0)
        }
      })

      return validated;
    },

    saveProject () {
      const params = {
        data: {
          companyId: this.companyId,
          name: this.name,
          description: this.description,
          staff: this.staff,
          stage: this.stage,
          stack: this.stack,
          methodology: this.methodology,
          breakdown: this.breakdown,
          perks: this.selectedPerks
        }
      }

      const handler = createdObject => {
        this.projects.push(createdObject.project)
        this.selectedProjectIndex = this.projects.length - 1
        return createdObject.project.id
      }

      this.$store.state.backend
        .post('/project/add', params)
        .then(ret => handler(ret.data))
        .then(createdProjectId => this.$emit('job', { id: createdProjectId }, 'project'))
        .catch(error => console.error(error))
    },

    emitProjectToParent () {
      if(this.validateForm()){
        if(this.selectedProjectIndex === '') {
          this.saveProject()
        } else {
          this.$emit('job', {id: this.projectId}, 'project')
        }
      }
    },

    matchWithExistingProjects () {
      const hasSameDataAsInputs = object => {
        return object.name == this.name
            && object.description == this.description
            && object.staff == this.staff
            && object.stage == this.stage
            && object.stack == this.stack
            && object.methodology == this.methodology
            && object.breakdown == this.breakdown
      }
      const projectIndex = this.projects.findIndex(object => hasSameDataAsInputs(object))
      this.selectedProjectIndex = projectIndex != -1 ? projectIndex : ''
      this.projectId = projectIndex != -1 ? this.projects[projectIndex].id : ''
    }
  }
}
</script>
<style lang="scss">
/*
  .card
    {
      box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
      border: 0;
    }
      */
</style>
