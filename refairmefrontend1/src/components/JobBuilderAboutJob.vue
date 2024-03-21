s<template lang="pug">
div
  form#aboutJob(@submit.stop.prevent="emitJobToParent")
    h1 About the job
    hr
    .form-row
      .col-6
        .form-group
          label(for='jobTitle') Job title
          input.form-control(name='jobTitle', type='text', v-model='title', placeholder='ex. Chief Data Analyst, Technical Support Engineer')
          small.invalid-feedback.help-block
        .form-group
          label(for='jobDescription') Job description
          textarea.form-control(name='jobDescription', rows='4', v-model='description', placeholder='What is this person being hired to do')
          small.invalid-feedback.help-block
        .form-group
          label(for='jobDescriptionPreview') Description preview
          vue-markdown(:source="description")
      .col-6
        .form-group
          label(for='contractType') Contract type
          //input.form-control(name="contractType" type='text' v-model='contractType' placeholder="Specify contract type")
          multiselect(
            v-model="contractType"
            :options="['B2B', 'UoP','Contract','Other']"
            :searchable="false",
            :multiple="true"
            :close-on-select="false",
            :show-labels="false"
            placeholder="Pick a type"
          )
          small.invalid-feedback.help-block
        .form-group
          label(for='jobLocation') Location
          input.form-control(name='jobLocation' type='text' v-model='location' placeholder='Enter work location')
          small.invalid-feedback.help-block
        .form-group
          label(for='recruitmentTime') Recruitment time
          input.form-control(name='recruitmentTime' type='number' v-model='duration' placeholder='Enter how many days from apply to offer')
          small.invalid-feedback.help-block
    br
    h5 SKILLS
    hr
    .form-row
      .col
        .form-group
          label#kwErrMsg(for='skillsMust') Must have
          Keywords(
            :keywords='this.keywords', @keywords='keywordsChange' 
            :skills='skills', @skills='skillsChange'
            placeholder="eg. PHP"
            name="skillsMust")
          small.invalid-feedback.help-block sample error
      //.col
        .form-group
          label#kwErrMsg(for='skillsNice') Nice to have
          Keywords(
            :skills='skillsNice', @skills='skillsNiceChange'
            placeholder="eg. Javascript"
            name="skillsNice")
          small.invalid-feedback.help-block sample error
    //.form-row
      .col
        h5 FRAMEWORKS
        hr
    //.form-row
      .col
        .form-group
          label#kwErrMsg(for='mustHave') Must have
          Keywords(
            :keywords='this.keywords', @keywords='keywordsChange' 
            :skills='skills', @skills='skillsChange'
            placeholder="eg. Angular"
            name="mustHave")
          small.invalid-feedback.help-block sample error
      .col
        .form-group
          label#kwErrMsg(for='mustHave') Nice to have
          Keywords(
            :keywords='this.keywords', @keywords='keywordsChange' 
            :skills='skills', @skills='skillsChange'
            placeholder="eg. Vue.js"
            name="mustHave")
          small.invalid-feedback.help-block sample error
    //.form-row
      .col
        h5 METHODOLOGIES
        hr
    //.form-row
      .col
        .form-group
          label#kwErrMsg(for='skillsMust') Must have
          Keywords(
            :keywords='this.keywords', @keywords='keywordsChange' 
            :skills='skills', @skills='skillsChange'
            placeholder="eg. Agile"
            name="skillsMust")
          small.invalid-feedback.help-block sample error
      .col
        .form-group
          label#kwErrMsg(for='skillsNice') Nice to have
          Keywords(
            :keywords='this.keywords', @keywords='keywordsChange' 
            :skills='skills', @skills='skillsChange'
            placeholder="eg. Lean"
            name="skillsNice")
          small.invalid-feedback.help-block sample error
    .form-row.mt-2
      .col-3
        label.col-form-label(for='travel') % Travel of role
        Slider(name='travel', ref="slider", :min='0', :max='100', tooltip='false', :interval='5', v-model='travelPercentage')
        span.small {{travelPercentage}}%
      .col-3
        label.col-form-label(for='remoteWork') % Remote work possible
        Slider(name='remoteWork', :min='0', :max='100', tooltip='false', :interval='5', v-model='remotePercentage')
        span.small {{remotePercentage}}%
      .col-3
        label.col-form-label(for='salary') Salary
        Slider(name="salary" :min='0', :max='50000', :interval='500', tooltip='false', :tooltip-dir="['left','right']", v-model='fund')
        span.small.float-left {{fund[0]}} PLN
        span.small.float-right {{fund[1]}} PLN
      .col-3
        label.col-form-label(for='experienceRequired') Experience required
        Slider(name='experienceRequired', :min='0', :max='20', tooltip='false', :interval='1', v-model='exp')
        span.small {{exp}} years
    .form-row.mt-4
      .form-group.col-4
        label
          input(type='checkbox', v-model='remote')
          |  This position requires remote
      .form-group.col-4
        label
          input(type='checkbox', v-model='relocation')
          |  This position requires relocation
      .form-group.col-4
        label
          input(type='checkbox', v-model='relocationPackage')
          |  Relocation package offered
    button.btn.btn-info.float-right(:type="jobToEdit ? 'button' : 'submit'" @click="jobToEdit ? updateJob() : ''")
      |{{!jobToEdit ? 'Next' : 'Update'}}
</template>
<script>
import Keywords from './Keywords'
import Slider from 'vue-slider-component'
import validation from '../validation.js'
import VueMarkdown from 'vue-markdown'
import Multiselect from 'vue-multiselect'

// TODO to be used instead of Keywords
// https://joshuajohnson.co.uk/Choices/

export default {
  components: {
    Keywords,
    Slider,
    VueMarkdown,
    Multiselect
  },
  
  // those props serve job editing component exclusively
  // TODO make them required if called from JobEdit component (check if possible)
  props: {
    companyId: Number,
    projectId: Number, //because db made it a String...
    jobToEdit: Object
  },

  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  },

  mounted () {
    this.copyJobEditHere()
    this.showSlider = true
  },

  watch: {
    keywords () {
      if(this.keywords.length > 0)
        validation.removeError($('#kwErrMsg'))
    },

    jobToEdit () {
      this.copyJobEditHere()
    },

    showSlider (val) {
      if(val)
        this.$nextTick(() => this.$refs.slider.refresh())
    }
  },

  data () {
    return {
      showSlider: false,
      title: '',
      description: '',
      location: '',
      duration: 21,
      contractType: [],
      keywords: [],
      skills: [],
      keywordsSkillsNice: [],
      skillsNice: [],
      frameworksMust: [],
      frameworksNice: [],
      methodologiesMust: [],
      methodologiesNice: [],
      travelPercentage: 20,
      remotePercentage: 20,
      currency: '$',
      fund: [5000, 12000],
      exp: 3,
      remote: false,
      relocation: false,
      relocationPackage: false
    }
  },

  methods: {
    keywordsChange (eventData) {
      this.keywords = eventData
    },

    skillsChange: (data) => this.skills = data,
    skillsNiceChange: (data) => this.skillsNice = data,

    changeCurrency (event) {
      const currencies = ['$','€','PLN']
      let index = currencies.indexOf(this.currency) + 1
      if(index == 3) index = 0
      this.currency = currencies[index]
    },

    copyJobEditHere (){
      Object.keys(this.jobToEdit).forEach((key,index) => {
        if(key !== 'companyId' && key !== 'projectId')
          this[key] = this.jobToEdit[key]
      })
    },

    emitJobToParent () {
      if(this.validateForm()){

        const params = {
          ...this.$data,
          projectId: this.projectId,
          companyId: this.companyId
        }

        this.$emit('job', params, 'job')
      }
    },

    updateJob () {
      if(this.validateForm()){

        const params = {
          ...this.$data,
          projectId: this.projectId,
          companyId: this.companyId
        }

        this.$store.state.backend
          .post(`/job/update/${this.jobToEdit.id}`, params)
          .then(ret => console.log(ret))
          .then(() => this.$emit('closeModal'))
          .then(ret => this.$store.dispatch('getJobs'))
          //.then(ret => this.$emit('fetch'))
          .catch(error => alert(error));
      }
    },

    validateForm () {
      let validated = true;
      const self = this;
      $("form#aboutJob label:not(.form-check-label)")
      .each(function(){
        if(validation.validateField($(this)) == false
          || validation.validateKeywords(self.keywords, $('#kwErrMsg')) == false){
          validated = false
          window.scroll(0,0)
        };
      })
      return validated;
    }
  }
}
</script>
<style lang="sass" scoped>
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
  .clickable
    cursor: pointer
  label
    font-weight: bold
    font-size: 16px
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
