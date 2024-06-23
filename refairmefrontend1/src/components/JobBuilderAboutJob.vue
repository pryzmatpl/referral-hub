<template>
  <div>
    <form id="aboutJob" @submit.stop.prevent="emitJobToParent">
      <h1>About the job</h1>
      <hr />
      <div class="form-row">
        <div class="col-6">
          <div class="form-group">
            <label for="jobTitle">Job title</label>
            <input
                class="form-control"
                name="jobTitle"
                type="text"
                v-model="title"
                placeholder="ex. Chief Data Analyst, Technical Support Engineer"
            />
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-group">
            <label for="jobDescription">Job description</label>
            <textarea
                class="form-control"
                name="jobDescription"
                rows="4"
                v-model="description"
                placeholder="What is this person being hired to do"
            ></textarea>
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-group">
            <label for="jobDescriptionPreview">Description preview</label>
            <vue-markdown :source="description" />
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label for="contractType">Contract type</label>
            <multiselect
                v-model="contractType"
                :options="['B2B', 'UoP', 'Contract', 'Other']"
                :searchable="false"
                :multiple="true"
                :close-on-select="false"
                :show-labels="false"
                placeholder="Pick a type"
            />
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-group">
            <label for="jobLocation">Location</label>
            <input
                class="form-control"
                name="jobLocation"
                type="text"
                v-model="location"
                placeholder="Enter work location"
            />
            <small class="invalid-feedback help-block"></small>
          </div>
          <div class="form-group">
            <label for="recruitmentTime">Recruitment time</label>
            <input
                class="form-control"
                name="recruitmentTime"
                type="number"
                v-model="duration"
                placeholder="Enter how many days from apply to offer"
            />
            <small class="invalid-feedback help-block"></small>
          </div>
        </div>
      </div>
      <br />
      <h5>SKILLS</h5>
      <hr />
      <div class="form-row">
        <div class="col">
          <div class="form-group">
            <label id="kwErrMsg" for="skillsMust">Must have</label>
            <Keywords
                :keywords="this.keywords"
                @keywords="keywordsChange"
                :skills="skills"
                @skills="skillsChange"
                placeholder="eg. PHP"
                name="skillsMust"
            />
            <small class="invalid-feedback help-block">sample error</small>
          </div>
          <div class="form-group">
            <label id="kwErrMsg" for="skillsNice">Nice to have</label>
            <Keywords
                :skills="skillsNice"
                @skills="skillsNiceChange"
                placeholder="eg. Javascript"
                name="skillsNice"
            />
            <small class="invalid-feedback help-block">sample error</small>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col">
          <h5>FRAMEWORKS</h5>
          <hr />
        </div>
      </div>
      <div class="form-row">
        <div class="col">
          <div class="form-group">
            <label id="kwErrMsg" for="mustHave">Must have</label>
            <Keywords
                :keywords="this.keywords"
                @keywords="keywordsChange"
                :skills="skills"
                @skills="skillsChange"
                placeholder="eg. Angular"
                name="mustHave"
            />
            <small class="invalid-feedback help-block">sample error</small>
          </div>
          <div class="col">
            <div class="form-group">
              <label id="kwErrMsg" for="mustHave">Nice to have</label>
              <Keywords
                  :keywords="this.keywords"
                  @keywords="keywordsChange"
                  :skills="skills"
                  @skills="skillsChange"
                  placeholder="eg. Vue.js"
                  name="mustHave"
              />
              <small class="invalid-feedback help-block">sample error</small>
            </div>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col">
          <h5>METHODOLOGIES</h5>
          <hr />
        </div>
      </div>
      <div class="form-row">
        <div class="col">
          <div class="form-group">
            <label id="kwErrMsg" for="skillsMust">Must have</label>
            <Keywords
                :keywords="this.keywords"
                @keywords="keywordsChange"
                :skills="skills"
                @skills="skillsChange"
                placeholder="eg. Agile"
                name="skillsMust"
            />
            <small class="invalid-feedback help-block">sample error</small>
          </div>
          <div class="col">
            <div class="form-group">
              <label id="kwErrMsg" for="skillsNice">Nice to have</label>
              <Keywords
                  :keywords="this.keywords"
                  @keywords="keywordsChange"
                  :skills="skills"
                  @skills="skillsChange"
                  placeholder="eg. Lean"
                  name="skillsNice"
              />
              <small class="invalid-feedback help-block">sample error</small>
            </div>
          </div>
        </div>
      </div>
      <div class="form-row mt-2">
        <div class="col-3">
          <label class="col-form-label" for="travel">% Travel of role</label>
          <Slider
              name="travel"
              ref="slider"
              :min="0"
              :max="100"
              tooltip="false"
              :interval="5"
              v-model="travelPercentage"
          />
          <span class="small">{{ travelPercentage }}%</span>
        </div>
        <div class="col-3">
          <label class="col-form-label" for="remoteWork">% Remote work possible</label>
          <Slider
              name="remoteWork"
              :min="0"
              :max="100"
              tooltip="false"
              :interval="5"
              v-model="remotePercentage"
          />
          <span class="small">{{ remotePercentage }}%</span>
        </div>
        <div class="col-3">
          <label class="col-form-label" for="salary">Salary</label>
          <Slider
              name="salary"
              :min="0"
              :max="50000"
              :interval="500"
              tooltip="false"
              :tooltip-dir="['left', 'right']"
              v-model="fund"
          />
          <span class="small float-left">{{ fund[0] }} PLN</span>
          <span class="small float-right">{{ fund[1] }} PLN</span>
        </div>
        <div class="col-3">
          <label class="col-form-label" for="experienceRequired">Experience required</label>
          <Slider
              name="experienceRequired"
              :min="0"
              :max="20"
              tooltip="false"
              :interval="1"
              v-model="exp"
          />
          <span class="small">{{ exp }} years</span>
        </div>
      </div>
      <div class="form-row mt-4">
        <div class="form-group col-4">
          <label>
            <input type="checkbox" v-model="remote" />
            This position requires remote
          </label>
        </div>
        <div class="form-group col-4">
          <label>
            <input type="checkbox" v-model="relocation" />
            This position requires relocation
          </label>
        </div>
        <div class="form-group col-4">
          <label>
            <input type="checkbox" v-model="relocationPackage" />
            Relocation package offered
          </label>
        </div>
      </div>
      <button
          class="btn btn-info float-right"
          :type="jobToEdit ? 'button' : 'submit'"
          @click="jobToEdit ? updateJob() : ''"
      >
        {{ !jobToEdit ? 'Next' : 'Update' }}
      </button>
    </form>
  </div>
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
