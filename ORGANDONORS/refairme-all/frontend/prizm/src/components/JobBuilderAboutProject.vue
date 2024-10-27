<template lang="pug">
  div
    h1 About the project
    hr
    form#aboutProject(@submit.prevent="emitProjectToParent")
      .form-row
        select(v-model="selectedProjectIndex")
          option(value="" disabled) choose project
          option(v-for="(value, index) in projects", :value="index") {{value.name}}
      .form-group
        label(for="projectTitle")
        input.form-control(name="projectTitle" type="text" placeholder="Enter project title" v-model="name")
      .form-group
        label(for="whyWorkOnProject") Why would someone want to work on the project?
        textarea.form-control(name="whyWorkOnProject"
                              v-model="description"
                              rows=3
                              type="text"
                              placeholder="Please describe why would someone want to work on the project")
        small.invalid-feedback.help-block
      .form-group
        label(for="teamSize") How many people work on project?
        input.form-control(name="teamSize" type="number" placeholder="Enter number" v-model="staff")
        small.invalid-feedback.help-block
      .form-group
        label(for="projectStage") What stage is project at?
        .form-check
          input.form-check-input(type="radio" name="projectStage" v-model="stage" value="greenfield")
          label.form-check-label greenfield
        .form-check
          input.form-check-input(type="radio" name="projectStage" v-model="stage" value="ongoing-development")
          label.form-check-label ongoing development
        .form-check
          input.form-check-input(type="radio" name="projectStage" v-model="stage" value="maintenance")
          label.form-check-label maintenance
      .form-group
        label(for="projectStack") What is the project techstack?
        textarea.form-control(name="projectStack"
                              v-model="stack"
                              rows=3
                              type="text"
                              placeholder="Please describe technology stack")
        small.invalid-feedback.help-block
      .form-group
        label(for="projectMethodology") Work methodology
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="issue tracking tool")
          label.form-check-label issue tracking tool
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="knowledge repository")
          label.form-check-label knowledge repository
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="code reviews")
          label.form-check-label code reviews
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="pair programming")
          label.form-check-label pair programming
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="unit testing")
          label.form-check-label unit testing
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="integration testing")
          label.form-check-label integration testing
        .form-check
          input.form-check-input(type="checkbox" name="checkbox" v-model="methodology" value="Agile/Lean/Waterfall")
          label.form-check-label Agile/Lean/Waterfall
      .form-row.m-0
        label Weekly work breakdown
      .form-row
        .col-6
          .form-group.row
            label.col-form-label.col-12.col-sm-6
              font-awesome-icon(:icon='tagIcon' v-bind:style="{color: labelColors[0]}")
              span  New features
            .col-12.col-sm-6
              .input-group
                input.form-control(type="number" v-model.number="breakdown.newFeatures")
                .input-group-append
                  span.input-group-text %
          .form-group.row
            label.col-form-label.col-12.col-sm-6
              font-awesome-icon(:icon='tagIcon', :style="{color: labelColors[1]}")
              span  Maintenance
            .col-12.col-sm-6
              .input-group
                input.form-control(type="number" v-model.number="breakdown.maintenance")
                .input-group-append
                  span.input-group-text %
          .form-group.row
            label.col-form-label.col-12.col-sm-6
              font-awesome-icon(:icon='tagIcon', :style="{color: labelColors[2]}")
              span  Client support
            .col-12.col-sm-6
              .input-group
                input.form-control(type="number" v-model.number="breakdown.support")
                .input-group-append
                  span.input-group-text %
          .form-group.row
            label.col-form-label.col-12.col-sm-6
              font-awesome-icon(:icon='tagIcon', :style="{color: labelColors[3]}")
              span  Document writing
            .col-12.col-sm-6
              .input-group
                input.form-control(type="number" v-model.number="breakdown.documentation")
                .input-group-append
                  span.input-group-text %
          .form-group.row
            label.col-form-label.col-12.col-sm-6
              font-awesome-icon(:icon='tagIcon', :style="{color: labelColors[4]}")
              span  Meetings
            .col-12.col-sm-6
              .input-group
                input.form-control(type="number" v-model.number="breakdown.meetings")
                .input-group-append
                  span.input-group-text %
        .col-6
          chart(chart-id='chart', :chart-data='chartData', :options='options', :height=270)
      button.btn.btn-success.float-right(type="submit") Next
</template>
<script>
import validation from '../validation.js'
import {
  mixins,
  //Doughnut,
  PolarArea
} from 'vue-chartjs'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faTag
} from '@fortawesome/fontawesome-free-solid'

var chart = {
  extends: PolarArea,
  mixins: [mixins.reactiveProp],
  props: ['options'],
  mounted() {
    if (this.chartData) {
      this.renderChart(this.chartData, this.options)
    }
  }
};

export default{
  components: {
    chart,
    FontAwesomeIcon
  },

  props: {
    companyId: {
      required: true,
      type: Number
    }
  },

  computed: {
    tagIcon: () => faTag
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
      breakdown: {
        newFeatures: 20,
        maintenance: 20,
        support: 20,
        documentation: 20,
        meetings: 20
      },
      chartData: null,
      options:{
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        layout: {
          padding: 5
        }
      },
      labelColors: ['#10B13A','#EC2E15','#EC8015','#117992','#FFB770']
    }
  },

  methods: {
    fetchAllProjects (id = '') {
      if(id !== ''){
        this.$store.state.backend
          .get('/project/get/all/' + id)
          .then(ret => {this.projects = ret.data; console.log(ret.data)})
          .catch(error => console.error(error))
      }
    },

    updateChart (v) {
      if(v != ''){
        this.chartData = {
          labels: [
            'New features',
            'Maintenance / bug fixing',
            'Client support',
            'Document writing',
            'Meetings'
          ],
          datasets: [{
            backgroundColor: this.labelColors,
            data: [
              this.breakdown.newFeatures,
              this.breakdown.maintenance,
              this.breakdown.support,
              this.breakdown.documentation,
              this.breakdown.meetings
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
        };
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
<style lang="sass">
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
</style>
