<template lang="pug">
div
  h2.mt-3.mb-4.text-center Welcome to Refair.me
  .container.shadow
    .row
      .col-12.inner-container
        .panel.panel-default
          .panel-body
            .row
              .col-6.d-none.d-md-block
                h1.text-left Recruitment, with you in a driver seat
                p(style="font-size: 18px; font-weight: 500") Every matching job, fast tracked and feedback to match your skills to best roles
              .col
              .col-12.col-md-5
                h1.text-left(style="font-size: 50px") Sign Up for Free
                form(action='', v-on:submit.prevent='signUserUp()')
                  small.help-block {{error}}
                  //.form-group
                    label(for='firstname') First name
                    input.form-control(type='text', name='firstname', placeholder='First name', v-model='firstname')
                  //.form-group
                    label(for='lastname') Last name
                    input.form-control(type='text', name='lastname', placeholder='Last name', v-model='lastname')
                  .form-row
                    .col-6
                      input.form-control(type='text', name='first_name', placeholder='First name', v-model='firstname')
                    .col-6
                      input.form-control(type='text', name='last_name', placeholder='Last name', v-model='lastname')
                  //.form-group
                    //label(for='email') Email
                  .form-row.mt-2
                    .col
                      input.form-control(type='email', name='email', placeholder='E-mail', v-model='email')
                  .form-row.mt-2
                    .col
                      //label(for='password') Password
                      .input-group
                        input.form-control(:type="[!isVisible ? 'password' : 'text']", name='password', placeholder="Password", v-model='password')
                        .input-group-append
                          //button.btn.btn-outline-secondary(type="button" @click="isVisible = !isVisible")
                          .input-group-text
                            font-awesome-icon(
                              :icon="isVisible ? eyeIcon : eyeSlashIcon" 
                              @click="isVisible = !isVisible"
                            )
                  multiselect.mt-2(
                    v-model="role"
                    :options="roleOptions"
                    label="name",
                    track-by="role"
                    :searchable="false",
                    :close-on-select="true",
                    :show-labels="false"
                    placeholder="Pick a role"
                  )
                  .form-row
                    .col
                      small.form-text.text-muted 
                        | By signing up you agree to the 
                        a(href="/privacypolicy")  Privacy Policy
                  p {{responseFromServer}}
                  button.btn.btn-info.btn-lg(:class="{disabled: responseFromServer != ''}" type='submit') Sign Up
                  div.text-center
                    small Already have an Refair.me account?
                      router-link(to="/auth/signin")  Log in
</template>
<script>
import helpy from '../helpers.js'
import Multiselect from 'vue-multiselect'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import {
  faEye,
  faEyeSlash
} from '@fortawesome/fontawesome-free-solid'
import { switchCase } from 'babel-types';

export default {
  components: {
    FontAwesomeIcon,
    Multiselect
  },

  computed: {
    eyeIcon: () => faEye,
    eyeSlashIcon: () => faEyeSlash
  },

  data () {
    return {
      firstname: '',
      lastname: '',
      email: '',
      password: '',
      error: '',
      role: '',
      roleOptions: [
        { name: 'Developer', role: 'candidate'},
        { name: 'Recruiter', role: 'recruiter'}
      ],
      isVisible: false,
      responseFromServer: ''
    }
  },

  methods: {
    signUserUp () {
      if(this.validateForm()){
        let headerRegister = {
          'Content-Type': 'multipart/form-data',
          'Authorization': 'Basic REGISTER',
          'Access-Control-Allow-Origin': '*',
        }
  
        this.$store.state.backend
          .post('/auth/signup', {
            firstname: this.firstname,
            lastname: this.lastname,
            email: this.email,
            password: this.password,
            role: this.role.role,
            headerRegister,
            dehashed: {
              SESSION_AUTH: false,
              SESSION_STATE: 2,
              SESSION_ID: '123ssdsdsd',
              ORIGIN: 'prizm',
              TIMESTAMP: '' //?
            }
          })
          .then(ret => {
            let data = ret.data;
            if(data.state === 'error'){
              this.error = data.message
            } else {
              this.responseFromServer = 'Please confirm your account in the email we sent you.'
              /*
              this.$router.push({
                name: 'SignIn',
                params: {
                  info: 'Please confirm your account in the email we sent you - you will be able to log in here after confirming your account'
                }
              })
              */
            }
          })
          .catch(error => alert(error.message));
      }
    },

    validateForm () {
      if(this.firstname == '' || this.lastname == '' || this.email == '' || this.password == '' || this.role == ''){
        this.error = 'Please fill all fields'
        return false
      } else {
        this.error = ''
        return true
      }
    }
  }
}
</script>
<style lang="sass" scoped>
  .container
    margin-top: 20px
    margin-bottom: 20px
  .inner-container
    background: white
    padding-bottom: 20px
    padding-top: 20px
    border-radius: 5px
  h1
    font-size: 60px
    margin-top: 40px
    margin-bottom: 20px
    text-align: center
  .help-block
    color: red
  .text-muted
    margin-bottom: 10px
  button
    width: 100%
  .shadow
    box-shadow: 0 4px 24px 0 rgba(37, 38, 94, 0.1)
    border: 0

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>