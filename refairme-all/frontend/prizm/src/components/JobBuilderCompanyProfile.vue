<template lang="pug">
div
  h1 Choose a company or create new
  hr
  form#companyProfile(@submit.prevent="emitCompanyToParent")
    .form-row
      //.col-12.col-md-3
        label Company logo
        img.img-thumbnail#img-upload(src="http://via.placeholder.com/300x300")
        .custom-file.mt-2
          input.custom-file-input#customFile(type="file")
          label.custom-file-label(for="customFile") Choose file
      .col-12.col-md-12
        select(v-model="selectedCompanyIndex")
          option(value="" disabled) choose company
          option(v-for="(value, index) in companies", :value="index") {{value.name}}
        .form-group
          label(for="companyName") Company name
          input.form-control(
            name="companyName"
            v-model="companyName"
            type="text"
            placeholder="Enter company name"
          )
          small.invalid-feedback.help-block
        //.form-group
          label(for="companyUrl") Company URL
          input.form-control(
            name="companyUrl"
            v-model="companyUrl"
            type="text"
            placeholder="Enter company url"
          )
          small.invalid-feedback.help-block
        //.form-group
          label(for="contactPerson") Contact person
          input.form-control(
            name="contactPerson"
            v-model="contactPerson"
            type="text"
            placeholder="Enter person responsible for contact"
          )
          small.invalid-feedback.help-block
        //.form-group
          label(for="contactEmail") Contact email
          input.form-control(
            name="contactEmail"
            v-model="contactEmail"
            type="email"
            placeholder="Enter contact email"
          )
          small.invalid-feedback.help-block
        .form-group
          label(for="aboutCompany") About company
            small(style="color: gray")  ({{aboutCompanyCharCount}} characters left)
          textarea.form-control(
            name="aboutCompany"
            v-model="aboutCompany"
            rows=3
            maxlength="360"
            type="text"
            placeholder="Please describe what your company does"
          )
          small.invalid-feedback.help-block
        button.btn.btn-success.float-right(type="submit") Next
</template>
<script>
import validation from '../validation.js'

export default {
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
        };
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
<style lang="sass">
  .card
    box-shadow: 0 2px 6px 0 hsla(0,0%,0%,0.1)
    border: 0
  .custom-file-label
    text-overflow: ellipsis
    overflow: hidden
    padding-right: 80px
    white-space: nowrap
  .custom-file-input
    cursor: pointer
</style>
