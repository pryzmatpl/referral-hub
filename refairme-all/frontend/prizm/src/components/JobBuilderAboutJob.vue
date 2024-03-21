s<template lang="pug">
div
  form#aboutJob(@submit.stop.prevent="emitJobToParent")
    h1 About the job
    hr
    .form-group
      label(for='jobTitle') Job title
      input.form-control(name='jobTitle', type='text', v-model='title', placeholder='ex. Chief Data Analyst, Technical Support Engineer')
      small.invalid-feedback.help-block
    .form-group
      label(for='jobDescription') Job description
      textarea.form-control(name='jobDescription', rows='4', v-model='description', placeholder='Describe the work to be the done')
      small.invalid-feedback.help-block
    .form-group
      label(for='jobDescriptionPreview') Description preview
      vue-markdown(:source="description")
    .form-group
      label(for='jobLocation') Location
      input.form-control(name='jobLocation' type='text' v-model='location' placeholder='Enter work location')
      small.invalid-feedback.help-block
    .form-group
      label(for='contractType') Contract type
      input.form-control(name="contractType" type='text' v-model='contractType' placeholder="Specify contract type")
      small.invalid-feedback.help-block
    br
    h5 WHAT ARE THE SKILL REQUIREMENTS?
    hr
    .form-group
      label#kwErrMsg(for='mustHave') Must have
      Keywords(:keywords='this.keywords', @keywords='keywordsChange' name="mustHave")
      small.invalid-feedback.help-block sds
    .form-row.mt-2
      .col-12.col-lg-6
        label(for='travel') % Travel of role
        .form-row
          .form-group.col-9
            Slider(name='travel', :min='0', :max='100', tooltip='hover', :interval='5', v-model='travelPercentage')
          .input-group.col-3
            input.form-control(type='text', v-model='travelPercentage')
            .input-group-append
              span.input-group-text %
    .form-row.mt-2
      .col-12.col-lg-6
        label(for='remoteWork') % Remote work possible
        .form-row
          .form-group.col-9
            Slider(name='remoteWork', :min='0', :max='100', tooltip='hover', :interval='5', v-model='remotePercentage')
          .input-group.col-3
            input.form-control(type='text', v-model='remotePercentage')
            .input-group-append
              span.input-group-text %
    .form-row
      .col-12.col-lg-6
        .form-group.col-12.p-0
          label.col-form-label(for='salary') Salary
          Slider(:min='0', :max='50000', :interval='500', tooltip='hover', :tooltip-dir="['left','right']", v-model='fund')
        .form-row
          .input-group.col-6
            .input-group-prepend.clickable(@click="changeCurrency")
              span.input-group-text.curr {{currency}}
            input.form-control(type='text', v-model='fund[0]')
          .input-group.col-6
            .input-group-prepend.clickable(@click='changeCurrency')
              span.input-group-text.curr {{currency}}
            input.form-control(type='text', v-model='fund[1]')
    .form-row.mt-2
      .col-12.col-lg-6
        .form-row
          label.col-form-label(for='experienceRequired') Experience required
          .form-group.col-9
            Slider(name='expslider', :min='0', :max='20', tooltip='hover', :interval='0.5', v-model='exp')
          .input-group.col-3
            input.form-control(type='text', v-model='exp')
            .input-group-append
              span.input-group-text yrs
    .form-group.col-12.col-lg-6.mt-2.pl-0
      label
        input(type='checkbox', v-model='remote')
        |  This position requires remote
    .form-group.col-12.col-lg-6.pl-0
      label
        input(type='checkbox', v-model='relocation')
        |  This position requires relocation
    .form-group.col-12.col-lg-6.pl-0
      label
        input(type='checkbox', v-model='relocationPackage')
        |  Relocation package offered
    button.btn.btn-success.float-right(:type="jobToEdit ? 'button' : 'submit'" @click="jobToEdit ? updateJob() : ''")
      |{{!jobToEdit ? 'Next' : 'Update'}}
</template>
<script>
import Keywords from './Keywords'
import Slider from 'vue-slider-component'
import validation from '../validation.js'
import VueMarkdown from 'vue-markdown'

// TODO to be used instead of Keywords
// https://joshuajohnson.co.uk/Choices/

export default {
  // those props serve job editing component exclusively
  // TODO make them required if called from JobEdit component (check if possible)
  components: {
    Keywords,
    Slider,
    VueMarkdown
  },

  props: {
    companyId: Number,
    projectId: Number,
    jobToEdit: Object
  },

  computed: {
    isAuthenticated: vm => vm.$store.getters.isAuthenticated
  },

  watch: {
    keywords () {
      if(this.keywords.length > 0)
        validation.removeError($('#kwErrMsg'))
    },

    jobToEdit () {
      Object.keys(this.jobToEdit).forEach((key,index) => {
        if(key !== 'companyId' && key !== 'projectId')
          this[key] = this.jobToEdit[key]
      })
    }
  },

  data () {
    return {
      title: '',
      description: '',
      location: '',
      contractType: [],
      keywords: [],
      travelPercentage: 20,
      remotePercentage: 20,
      currency: '$',
      fund: [3000, 15000],
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

    changeCurrency (event) {
      const currencies = ['$','€','PLN']
      let index = currencies.indexOf(this.currency) + 1
      if(index == 3) index = 0
      this.currency = currencies[index]
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
          .post('/job/update/' + this.jobToEdit.id, params)
          .then(() => this.$emit('closeModal'))
          .then(ret => this.$emit('fetch'))
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
</style>
