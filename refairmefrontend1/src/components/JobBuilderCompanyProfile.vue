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

export default {
  components: {
    Multiselect
  },

  computed: {
    aboutCompanyCharCount: vm => 360 - vm.aboutCompany.length
  },

  watch: {
    selectedCompanyIndex () {
      if(this.selectedCompanyIndex !== ''){
        const selectedId = this.companies[this.selectedCompanyIndex]
        this.companyId = selectedId.id
        this.companyName = selectedId.name
        this.aboutCompany = selectedId.description
      }
    },
    companyName: 'matchWithExistingCompanies',
    aboutCompany: 'matchWithExistingCompanies'
  },

  mounted() {
    this.fetchAllCompanies()

    const self = this;
    // TODO get rid of jQuery
    $(document).on('change', '.custom-file :file', function() {
      //console.log('file changed');
      $('.custom-file-label').css('color','inherit')
      var input = $(this),
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [label]);
    });

    $('.custom-file :file').on('fileselect', function(event, label) {
      let input = $(this).parents('.custom-file').find('label'),
          log = label;
      if (input.length) {
        input[0].innerText = log;
      } else {
        if (log) alert(log);
      }
    });

    $("#customFile").change(function() {
      self.readURL(this);
    });

    $('form input, textarea').change(function(){
      validation.removeError(this)
    })
  },

  data () {
    return {
      companyName: '',
      //companyUrl: '',
      //contactPerson: '',
      //contactEmail: '',
      aboutCompany: '',
      companies: [],
      companyId: '',
      selectedCompanyIndex: ''
    }
  },

  methods: {
    fetchAllCompanies () {
      const handler = data => {
        this.companies = data
      }

      this.$store.state.backend
        .get('/company/get/all')
        .then(ret => handler(ret.data))
        .catch(error => console.error(error))
    },

    emitCompanyToParent () {
      if(this.validateForm()){
        if(this.selectedCompanyIndex === ''){
          this.saveCompany()
        } else {
          this.$emit('job', { id: this.companyId }, 'company')
        }
      }
    },

    validateForm () {
      let validated = true;

      $("form#companyProfile label:not(.form-check-label)")
      .each(function(){
        if(validation.validateField($(this)) == false){
          validated = false
          window.scroll(0,0)
        }
      })

      return validated;
    },

    saveCompany () {
      const params = {
        data: {
          name: this.companyName,
          description: this.aboutCompany
        }
      }

      const handler = createdObject => {
        this.companies.push(createdObject.company)
        this.selectedCompanyIndex = this.companies.length - 1
        return createdObject.company.id
      }

      this.$store.state.backend
        .post('/company/add', params)
        .then(ret => handler(ret.data))
        .then(createdCompanyId => this.$emit('job', { id: createdCompanyId }, 'company'))
        .catch(error => console.error(error))
    },

    readURL (input) {
      if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
          $('#img-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    },

    matchWithExistingCompanies () {
      const hasSameDataAsInputs = object => {
        return object.name == this.companyName
            && object.description == this.aboutCompany
      }
      const companyIndex = this.companies.findIndex(object => hasSameDataAsInputs(object))
      this.selectedCompanyIndex = companyIndex != -1 ? companyIndex : ''
      this.companyId = companyIndex != -1 ? this.companies[companyIndex].id : ''
    }
  }
}
</script>
<style lang="scss" scoped>
.card {
  box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1);
  border: 0;
}

.custom-file-label {
  text-overflow: ellipsis;
  overflow: hidden;
  padding-right: 80px;
  white-space: nowrap;
}

.custom-file-input {
  cursor: pointer;
}
</style>
