<template>
  <div>
    <h1>Choose a company or create new</h1>
    <hr />
    <form id="companyProfile" @submit.prevent="emitCompanyToParent">
      <div class="col-8">
        <multiselect v-model="selectedCompanyIndex" label="name" track-by="id"
          :options="[{name: `ADD NEW COMPANY...`}, ...companies]" :searchable="false" :allow-empty="true"
          :close-on-select="true" :show-labels="true" placeholder="Choose company" />
        <div v-if="selectedCompanyIndex.name === `ADD NEW COMPANY...`">
          <div class="form-row">
            <label>Company logo</label>
            <img class="img-thumbnail" id="img-upload" :src="imageSrc" />
            <div class="custom-file mt-2">
              <input type="file" class="custom-file-input" id="customFile" @change="handleFileUpload" />
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
          </div>
          <div class="form-group" >
            <label for="companyName">Company name</label>
            <input type="text" class="form-control" v-model="companyName" placeholder="Enter company name" />
          </div>
          <div class="form-group" v-if="selectedCompanyIndex.name === `ADD NEW COMPANY...`">
            <label for="aboutCompany">About company <small style="color: gray">({{ aboutCompanyCharCount }} characters
                left)</small></label>
            <textarea class="form-control" v-model="aboutCompany" rows="3" maxlength="360"
              placeholder="Please describe what your company does"></textarea>
          </div>
        </div>
        <button type="submit" class="btn btn-success float-right my-2">Next</button>
      </div>
    </form>
  </div>
</template>
<script>
import validation from '../validation.js'
import Multiselect from 'vue-multiselect'


export default {
  components: {
    Multiselect,
  },

  props: {
    companyId: {
      required: true,
      type: Number
    }
  },

  watch: {
    companyId: {
      handler () {
      },
      immediate: true
    },
    selectedCompanyIndex(newValue) {
        if (newValue === null) {
            this.selectedCompanyIndex = ""; // Convert null to empty string
        }
    }
  },

  mounted () {
    this.fetchCompanies()
  },

  data () {
    return {
      companyId: '',
      companies: [],
      name: '',
      selectedCompanyIndex: '',
      description: '',
    }
  },

  methods: {
    emitCompanyToParent () {
      if(this.validateForm()){
        if(this.selectedCompanyIndex.name === `ADD NEW COMPANY...`) {
          this.saveCompany()
        } else {
          this.$emit('job', {id: this.selectedCompanyIndex.id}, 'company')
        }
      }
    },

    fetchCompanies() {
      const unique_id = this.$store.state.dehashedData.USER_ID

      this.$store.state.backend
        .post('/company/get/all/' + unique_id)
        .then(response => {
          console.log(response)
          this.companies = response.data.companies
        })
    },

    saveCompany() {
      const unique_id = this.$store.state.dehashedData.USER_ID

      try {
        const companyToSave = { name: this.companyName, description: this.aboutCompany }
        this.$store.state.backend
          .post('/company/add', { data: { ...companyToSave, id: unique_id } })
          .then(response => {
            console.log(response)
            this.companies.push(companyToSave)
            console.log(response.data.company.id)
            this.$emit('job', { id: response.data.company.id }, 'company')
          })
      } catch (error) {
        console.error(error);
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
  }
}
</script>
<style lang="scss">

</style>
